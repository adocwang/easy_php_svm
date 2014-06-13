easy_php_svm
============

a php class based on php's libSVM,expanded many useful functions.

class EasyPhpSvm
    propertys:
        debug:  if debug mode is opening,default false.
        cRange:  an array controls autosearch rang of cost parameter in libsvm.format:array(from,to,step),default array(-7,7,2)
        gRange:  an array controls autosearch rang of gamma parameter in libsvm.format:array(from,to,step),default array(-7,7,2)
        cMinStep:  the accurate searching step of cost parameter,default 0.5
        gMinStep:  the accurate searching step of gamma parameter,default 0.5
    
    functions:
        setcRange($start,$end,$step,$minStep):set step range of cost parameter
        setgRange($start,$end,$step,$minStep):set step range of gamma parameter
        gridSearchCG($data):auto search best c,g parameter using gridSearch and cross validate,$data could be a svm light file url or an array like this array(-1,1=>0.87263,2=>9767,3=>0,4=>1)
