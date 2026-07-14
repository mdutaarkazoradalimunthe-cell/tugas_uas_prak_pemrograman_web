"""
ETL: USDA FoodData Central → CSV mapping
==========================================
Gunakan script ini untuk mengambil data gizi dari USDA FoodData Central API
dan mencocokkannya dengan database bahan_makanan lokal (TKPI/Panganku).

Requirement: pip install requests

Cara pakai:
  1. Daftar API key gratis di https://fdc.nal.usda.gov/api-key-signup.html
  2. Set environment variable USDA_API_KEY=your_key_here
  3. python _fetch_usda.py --search "ayam"        # cari satu bahan
  4. python _fetch_usda.py --batch mapping.csv    # cocokkan banyak bahan dari CSV

Output: CSV dengan kolom: id_lokal, nama_lokal, fdc_id, nama_usda, kalori, protein, karbo, lemak
"""

import os
import sys
import csv
import json
import time
import argparse
from urllib.request import Request, urlopen
from urllib.parse import quote
from urllib.error import HTTPError

API_KEY = os.environ.get("USDA_API_KEY", "DEMO_KEY")
BASE_URL = "https://api.nal.usda.gov/fdc/v1"


def fetch_food(query, page_size=5):
    """Cari food di USDA FDC API."""
    url = f"{BASE_URL}/foods/search?api_key={API_KEY}&query={quote(query)}&pageSize={page_size}&dataType=Foundation,SR Legacy"
    req = Request(url, headers={"User-Agent": "Mozilla/5.0"})
    try:
        with urlopen(req, timeout=15) as resp:
            data = json.loads(resp.read())
    except HTTPError as e:
        print(f"  HTTP Error {e.code}: {e.reason}", file=sys.stderr)
        return []
    return data.get("foods", [])


def extract_nutrients(food):
    """Ambil kalori, protein, karbo, lemak dari response USDA."""
    nutrients = {"kalori": 0, "protein": 0, "karbo": 0, "lemak": 0}
    mapping = {
        1008: "kalori",   # Energy (kcal)
        1003: "protein",  # Protein
        1005: "karbo",    # Carbohydrate
        1004: "lemak",    # Total Fat
    }
    for nutrient in food.get("foodNutrients", []):
        nid = nutrient.get("nutrientId")
        if nid in mapping:
            value = nutrient.get("value") or 0
            nutrients[mapping[nid]] = round(value, 2)
    return nutrients


def search_and_display(query):
    """Cari dan tampilkan hasil."""
    print(f"\nMencari: '{query}'")
    foods = fetch_food(query)
    if not foods:
        print("  Tidak ditemukan.")
        return

    for i, food in enumerate(foods[:5]):
        desc = food.get("description", "N/A")
        brand = food.get("brandName") or ""
        fdc_id = food.get("fdcId")
        nutrients = extract_nutrients(food)

        print(f"\n  [{i+1}] FDC#{fdc_id} — {desc}" + (f" ({brand})" if brand else ""))
        print(f"       Kalori: {nutrients['kalori']} | Protein: {nutrients['protein']}g | "
              f"KH: {nutrients['karbo']}g | Lemak: {nutrients['lemak']}g")


def batch_from_csv(csv_path, output_path="usda_mapping_result.csv"):
    """Baca CSV mapping, cari tiap bahan di USDA, simpan hasil."""
    with open(csv_path, "r", encoding="utf-8") as f:
        reader = csv.DictReader(f)
        rows = list(reader)

    results = []
    for i, row in enumerate(rows):
        nama_lokal = row.get("nama_bahan", "").strip()
        id_lokal = row.get("id", "")
        if not nama_lokal:
            continue

        print(f"[{i+1}/{len(rows)}] {nama_lokal}... ", end="", flush=True)
        foods = fetch_food(nama_lokal, page_size=1)

        if foods:
            food = foods[0]
            nutrients = extract_nutrients(food)
            results.append({
                "id_lokal": id_lokal,
                "nama_lokal": nama_lokal,
                "fdc_id": food.get("fdcId"),
                "nama_usda": food.get("description", ""),
                "kalori": nutrients["kalori"],
                "protein": nutrients["protein"],
                "karbo": nutrients["karbo"],
                "lemak": nutrients["lemak"],
            })
            print(f"OK → FDC#{food.get('fdcId')} ({food.get('description', '')[:40]})")
        else:
            results.append({
                "id_lokal": id_lokal,
                "nama_lokal": nama_lokal,
                "fdc_id": "",
                "nama_usda": "",
                "kalori": "",
                "protein": "",
                "karbo": "",
                "lemak": "",
            })
            print("TIDAK DITEMUKAN")

        # Rate limiting: 1 request/detik untuk DEMO_KEY
        if API_KEY == "DEMO_KEY":
            time.sleep(1.1)

    # Simpan hasil
    with open(output_path, "w", newline="", encoding="utf-8") as f:
        writer = csv.DictWriter(f, fieldnames=[
            "id_lokal", "nama_lokal", "fdc_id", "nama_usda",
            "kalori", "protein", "karbo", "lemak"
        ])
        writer.writeheader()
        writer.writerows(results)

    print(f"\nHasil disimpan ke: {output_path}")
    return results


if __name__ == "__main__":
    parser = argparse.ArgumentParser(description="USDA FoodData Central ETL")
    parser.add_argument("--search", type=str, help="Cari satu bahan makanan")
    parser.add_argument("--batch", type=str, help="Cocokkan banyak bahan dari CSV (kolom: id,nama_bahan)")
    parser.add_argument("--output", type=str, default="usda_mapping_result.csv", help="Output CSV untuk batch mode")
    args = parser.parse_args()

    if args.search:
        search_and_display(args.search)
    elif args.batch:
        batch_from_csv(args.batch, args.output)
    else:
        print(__doc__)
        print("\nContoh:")
        print("  python _fetch_usda.py --search \"nasi putih\"")
        print("  python _fetch_usda.py --batch daftar_bahan.csv")
