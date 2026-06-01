# VTurb / ConverteAI player settings

Sales and member pages embed VTurb smartplayers with `data-smr-guard="1"` and load `public/assets/vturb-player-guard.min.js` to enforce **no autoplay** and **muted by default** on the injected `<video>` element.

Also configure each player in the VTurb dashboard so dashboard-side Smart Autoplay does not fight the site behavior.

## Player IDs

| Page | Player ID (embed `id`) | VTurb video ID |
|------|------------------------|----------------|
| Sales VSL (all funnels) | `vid-6a03e48213e119642182af7b` | `6a03e48213e119642182af7b` |
| Member — Luna opening | `vid-69f9471081d684a7fe711cd9` | `69f9471081d684a7fe711cd9` |
| Member — Ritual welcome | `vid-69f8b30bfe543f779059745a` | `69f8b30bfe543f779059745a` |

## Dashboard checklist (per player)

1. **Disable Smart Autoplay** (and any “start on any page interaction” option).
2. **Start muted** if the panel offers it.
3. After changes, hard-refresh the site (or clear cache) when verifying.

## Deploy note

After editing `frontend/js/lib/vturb-player-guard.js`, run `npm run build` so `public/assets/vturb-player-guard.min.js` is updated.
