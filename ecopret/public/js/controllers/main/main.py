from synonymes import * 
import sys
import json
def chercherMot():
    if sys.argv[1] == None:
        return ["erreur manque arguments"]
    else:
        mots = synonymes.cnrtl(sys.argv[1])
        print (json.dumps(mots))
    
chercherMot()
