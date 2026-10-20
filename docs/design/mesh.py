"""Builds the hero SVG: the Makerere tower from the IoT-ra logo, drawn as a node/link mesh."""
Y = 70  # room above the roof for the signal mark
N = {  # traced from public/img/logo.jpeg (shifted, same proportions)
 "A":(95,55),"B":(185,18),"C":(308,18),"D":(390,68),
 "E":(95,175),"F":(390,175),"G":(28,208),"H":(463,208),
 "S":(185,182),"T":(308,182),
 "I":(95,443),"J":(185,443),"K":(308,443),"L":(390,443),
 "M":(28,480),"N":(463,480),"Q":(95,508),"R":(390,508),"O":(28,540),"P":(463,540),
 "U":(185,108),"V":(140,140),"BB":(250,96),"W":(380,232),"CC":(338,300),"X":(160,360),
 "Y":(420,372),"DD":(58,330),"EE":(440,300),"Z":(320,488),"AA":(160,496),
}
FRAME = "A-B B-C C-D A-E E-I I-Q D-F F-L L-R G-M M-O H-N N-P E-G F-H I-M L-N O-P I-J J-K K-L S-J T-K".split()
WEB = ("A-U U-C B-V V-E U-BB BB-D BB-T V-S E-X X-J S-X T-CC CC-L F-W W-CC W-Y H-Y Y-N X-AA AA-Q "
       "AA-Z Z-K Z-R DD-G DD-I DD-X EE-H EE-L EE-Y CC-Y U-S C-T B-U Q-O R-P").split()
BIG = set("A B C D E F G H I J K L M N O P Q R".split())
p = lambda k: (N[k][0], N[k][1] + Y)
out = ['<svg class="mesh" viewBox="0 0 490 640" role="img" aria-labelledby="mesh-title">',
       '<title id="mesh-title">The Makerere tower from the IoT-ra logo, drawn as a network of nodes and links</title>',
       '<path class="mesh-ground" d="M0 628 L490 614 L490 626 L0 634 Z"/>']
for e in WEB:
    a, b = e.split("-"); (x1,y1),(x2,y2) = p(a), p(b)
    out.append(f'<line class="mesh-web" x1="{x1}" y1="{y1}" x2="{x2}" y2="{y2}"/>')
for e in FRAME:
    a, b = e.split("-"); (x1,y1),(x2,y2) = p(a), p(b)
    out.append(f'<line class="mesh-frame" x1="{x1}" y1="{y1}" x2="{x2}" y2="{y2}"/>')
(sx,sy),(tx,ty) = p("S"), p("T")
out.append(f'<path class="mesh-frame" d="M{sx} {sy} Q{(sx+tx)/2} {sy-70} {tx} {ty}"/>')  # the arch over the door
# data packets climbing both sides of the tower to the signal mark
lx = [p(k) for k in "O M I E A B".split()]; rx = [p(k) for k in "P N L F D C".split()]
for side, pts in (("l", lx), ("r", rx)):
    d = "M" + " L".join(f"{x} {y}" for x, y in pts) + " L246 88 L246 46"
    out.append(f'<path class="mesh-packet mesh-packet-{side}" pathLength="100" d="{d}"/>')
for i, k in enumerate(sorted(N, key=lambda k: (N[k][1], N[k][0]))):
    x, y = p(k); r = 9 if k in BIG else 5
    out.append(f'<circle class="mesh-node" style="--i:{i}" cx="{x}" cy="{y}" r="{r}"/>')
# the signal mark: ((•)) like the logo's antenna
out += ['<g class="mesh-signal">', '<circle cx="246" cy="40" r="7"/>',
        '<path d="M228 22 A26 26 0 0 0 228 58"/><path d="M264 22 A26 26 0 0 1 264 58"/>',
        '<path d="M214 8 A46 46 0 0 0 214 72"/><path d="M278 8 A46 46 0 0 1 278 72"/>', '</g>', '</svg>']
print("\n".join(out))
