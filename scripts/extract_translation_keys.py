import json
import os
import re

APP_LIB = os.path.join(os.path.dirname(__file__), "../../../App/user_app/lib")
keys = {}
pat = re.compile(r"\.tr\(['\"]([^'\"]+)['\"],\s*fallback:\s*['\"]([^'\"]*)['\"]")

for root, _, files in os.walk(APP_LIB):
    for f in files:
        if not f.endswith(".dart"):
            continue
        path = os.path.join(root, f)
        with open(path, encoding="utf-8") as fh:
            text = fh.read()
        for m in pat.finditer(text):
            keys[m.group(1)] = m.group(2)

# AppStrings fallbacks (English)
app_strings_path = os.path.join(APP_LIB, "core/l10n/app_strings.dart")
with open(app_strings_path, encoding="utf-8") as fh:
    text = fh.read()
for m in re.finditer(r"'([^']+)':\s*'([^']*(?:\\.[^']*)*)'", text):
    k, v = m.group(1), m.group(2)
    if k not in keys:
        keys[k] = v

out = os.path.join(os.path.dirname(__file__), "translation_keys.json")
with open(out, "w", encoding="utf-8") as fh:
    json.dump(keys, fh, ensure_ascii=False, indent=2)
print(f"Wrote {len(keys)} keys to {out}")
