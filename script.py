import codecs
with open(r'c:\xampp\htdocs\Troubleshooting-Komputer\docs\skripsi_text.txt', 'r', encoding='utf-16le') as f:
    content = f.read()
with open(r'c:\xampp\htdocs\Troubleshooting-Komputer\docs\skripsi_text_utf8.txt', 'w', encoding='utf-8') as fw:
    fw.write(content)
