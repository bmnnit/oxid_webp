#!/bin/bash

#copy to image folder.. and run
#adjust number of cores below -P8 in this case

convertWebp() {

  DIR=$(dirname $1);
  IMGWEBP=$DIR/$(basename $1 jpg)webp

  if [ ! -f "$IMGWEBP" ]; then
    cwebp -quiet $1 -o $IMGWEBP
  fi
  #sonst failed xargs beim ersten fehler..
  exit 0
}

export -f convertWebp

find . -name \*.jpg -type f -print0 | xargs -0 -n1 -P8  -I {} bash -c  'convertWebp "{}"'
find . -name \*.png -type f -print0 | xargs -0 -n1 -P8  -I {} bash -c  'convertWebp "{}"'

#single cpu.. tzzzz
#find . -name \*.jpg -exec bash -c 'convertWebp "{}"'  \;
                                                              
