from synonymes import cnrtl
import sys
import json
def chercherMot():
    if sys.argv[1] == None:
        return ["erreur manque arguments"]
    else:
        mots = cnrtl(sys.argv[1])
        print (json.dumps(mots))
    
chercherMot()
