import json

isevo = []

with open('core/json/pokedex.tree.json') as f:
    content = json.load(f)

    with open('base_stats.json') as f2:
        base_stats = json.load(f2)
        for entry in sorted(base_stats):
            evos = base_stats[entry].get('evolutions')
            if len(evos) > 0:
                isevo.append(int(evos[0]))
            if len(evos) > 1:
                isevo.append(int(evos[1]))
            if content.get(int(entry)) is None and int(entry) not in isevo:
                if len(evos) == 0:
                    content[int(entry)] = {"id": int(entry)}
                if len(evos) > 0:
                    content[int(entry)] = {"id": int(entry), "evolutions": []}
                    content[int(entry)] ['evolutions'].append(
                        {"id": int(evos[0]), "candies": (50 if len(evos) == 1 else 25)})
                if len(evos) > 1:
                    content[int(entry)] ['evolutions'].append(
                        {"id": int(evos[1]), "candies": 100})

    with open('core/json/pokedex.tree.json', 'w') as outfile:
        json.dump(content, outfile, indent=2, sort_keys=True)
