import os, sys
import Image

size = 128, 128

for infile in sys.argv[1:]:
    outfile = "." + os.path.splitext(infile)[0]
    if infile != outfile:
        try:
            im = Image.open(infile)
            im.thumbnail(size, Image.ANTIALIAS)
            im.save(outfile + ".png", "PNG")
        except IOError:
            print "cannot create thumbnail for '%s'" % infile