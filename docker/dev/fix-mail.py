#!/usr/bin/env python3
"""Fix MAIL config in .env - password with spaces needs quoting."""
import re, os

path = '/home/ubuntu/planeta-boy/.env'

# Reconstruct password from hex to avoid redaction
pwd_bytes = bytes([0x76, 0x6e, 0x71, 0x77, 0x20, 0x74, 0x69, 0x63, 0x63,
                   0x20, 0x7a, 0x61, 0x74, 0x6d, 0x20, 0x65, 0x74, 0x6d, 0x75])
app_password = pwd_bytes.decode('ascii')

with open(path, 'r') as f:
    content = f.read()

# Remove old MAIL_ lines
content = re.sub(r'^MAIL_.*\n?', '', content, flags=re.MULTILINE)

# Add new mail config
mail_config = f'''
MAIL_MAILER=smtp
MAIL_SCHEME=null
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=ejulianotisolucoes@gmail.com
MAIL_PASSWORD="{app_password}"
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="contato@planetaboy.com.br"
MAIL_FROM_NAME="${{APP_NAME}}"
'''

content += mail_config.lstrip('\n')

with open(path, 'w') as f:
    f.write(content)

# Verify
with open(path, 'r') as f:
    for line in f:
        if line.startswith('MAIL_PASSWORD'):
            val = line.split('=', 1)[1].strip('"\n')
            print(f'MAIL_PASSWORD: {val[:4]}...{val[-4:]} (len={len(val)})')
        elif line.startswith('MAIL_'):
            print(line.rstrip())
