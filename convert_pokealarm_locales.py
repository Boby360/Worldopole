import json


with open('core/json/locales/DE/pokes.json') as f:
    content = json.load(f)
    pokemon = content.get('pokemon', {})

    with open('de.json') as f2:
        locale = json.load(f2).get('pokemon', [])
        for entry in locale:
            pkm = pokemon.get(str(int(entry)))
            if pkm is None:
                pokemon[int(entry)] = {
                    'name': locale[entry],
                    'description': ''
                }
            else:
                pkm['name'] = locale[entry]

    with open('core/json/locales/DE/pokes.json', 'w') as outfile:
        json.dump(content, outfile, indent=2, sort_keys=False)
