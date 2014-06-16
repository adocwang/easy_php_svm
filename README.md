easy_php_svm
============

a php class based on php's libSVM,expanded many useful functions.

class EasyPhpSvm:

propertys:

 - public debug:  if debug mode is opening,default false.
 - private cRange:  an array controls autosearch rang of cost parameter in
   libsvm.format:array(from,to,step),default array(-7,7,2)
 - private gRange:  an array controls autosearch rang of gamma parameter in
   libsvm.format:array(from,to,step),default array(-7,7,2)
 - private cMinStep:  the accurate searching step of cost parameter,default 0.5
 - private gMinStep:  the accurate searching step of gamma parameter,default 0.5

functions:

 - public setcRange(`$start,$end,$step,$minStep`):set step range of cost parameter
 - public setgRange(`$start,$end,$step,$minStep`):set step range of gamma parameter
 - public gridSearchCG(`$data`):auto search best c,g parameter using gridSearch and cross validate,$data could be an svm light file url or an array like this array(-1,1=>0.87263,22=>9767,43=>0,44=>1)
