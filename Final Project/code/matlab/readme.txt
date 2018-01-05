1.Put libsvm-3.22 file into the path that you install matlab in the toolbox catalog
2.Open matlab,setpath-add path:libsvm-matlab catalog, then open this path
3.Input command:mex -setup,then choose c++
4.Input command:make,then this catalog appear two files:svmtrain.mexw64 and svmpredict.mexw64
5.Change two files' name into libsvmtrain.mexw64 and libsvmpredict.mexw64
6.Put svmpredict1 and historical data into the libsvm-matlab catalog
7.Run,it shows the result and write result into a predict.csv file.
