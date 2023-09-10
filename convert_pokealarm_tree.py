import json

isevo = []

# "evolutions": {
#     "6": {
#         "pokemon": 6,
#         "form": 178,
#         "candyCost": 100
#     }
# }

with open('core/json/pokedex.tree.json') as f:
    content = json.load(f)

    with open('pokemon_data.json') as f2:
        base_stats = json.load(f2)
        for entry in sorted(base_stats):
            evos = base_stats[entry].get('evolutions')
            if evos:
                evo_id = list(evos.keys())[0]
                isevo.append(int(evo_id))
                if content.get(int(entry)) is None and int(entry) not in isevo:
                    content[int(entry)] = {"id": int(entry)}
                    content[int(entry)]['evolutions'] = [{"id": int(evo_id), "candies": evos[evo_id].get('candyCost')}]
                    evos2 = base_stats[evo_id].get('evolutions')
                    if evos2:
                        evo2_id = list(evos2.keys())[0]
                        isevo.append(int(evo2_id))
                        content[int(entry)]['evolutions'][0]['evolutions'] = [{"id": int(evo2_id), "candies": evos2[evo2_id].get('candyCost')}]


    with open('core/json/pokedex.tree.json', 'w') as outfile:
        json.dump(content, outfile, indent = 2, sort_keys = False)
