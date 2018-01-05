%written by Zhe Chang
%assisted by Xiaoyi Tang
%Debugged by Feng Rong
data_1=importdata('GOOG_historical.csv',',');
data_2=importdata('MSFT_historical.csv',',');
data_3=importdata('GE_historical.csv',',');
data_4=importdata('YHOO_historical.csv',',');
data_5=importdata('PTR_historical.csv',',');
data_6=importdata('IBM_historical.csv',',');
data_7=importdata('SNGX_historical.csv',',');
data_8=importdata('ISRG_historical.csv',',');
data_9=importdata('EGT_historical.csv',',');
data_10=importdata('AAPL_historical.csv',',');

xx = 1:10;
xx=xx';
predict=[];
aa={'goog'};
for n=1:10
        eval(['y','=','data_',num2str(n),'.data(1:10,4)',';']);
        yy=flipud(y);
        model = libsvmtrain(yy,xx,'-s 3 -t 2 -c 2.2 -g 2.8 -p 0.001');
        testx = 11;
        [ptesty] = libsvmpredict(4,testx,model);
        lastdate_price = yy(10);
        predict=[predict;lastdate_price,ptesty]
end
        rtn=xlswrite('predict.csv',cellstr('GOOG'),'A1:A1');
        rtn=xlswrite('predict.csv',cellstr('MSFT'),'A2:A2');
        rtn=xlswrite('predict.csv',cellstr('GE'),'A3:A3');
        rtn=xlswrite('predict.csv',cellstr('YHOO'),'A4:A4');
        rtn=xlswrite('predict.csv',cellstr('PTR'),'A5:A5');
        rtn=xlswrite('predict.csv',cellstr('IBM'),'A6:A6');
        rtn=xlswrite('predict.csv',cellstr('SNGX'),'A7:A7');
        rtn=xlswrite('predict.csv',cellstr('ISRG'),'A8:A8');
        rtn=xlswrite('predict.csv',cellstr('EGT'),'A9:A9');
        rtn=xlswrite('predict.csv',cellstr('AAPL'),'A10:A10');
        xlswrite('predict.csv',predict,'B1:D10');
        rtn=xlswrite('predict.csv',cellstr('2017/4/28'),'D1:D1');
        rtn=xlswrite('predict.csv',cellstr('2017/4/28'),'D2:D2');
        rtn=xlswrite('predict.csv',cellstr('2017/4/28'),'D3:D3');
        rtn=xlswrite('predict.csv',cellstr('2017/4/28'),'D4:D4');
        rtn=xlswrite('predict.csv',cellstr('2017/4/28'),'D5:D5');
        rtn=xlswrite('predict.csv',cellstr('2017/4/28'),'D6:D6');
        rtn=xlswrite('predict.csv',cellstr('2017/4/28'),'D7:D7');
        rtn=xlswrite('predict.csv',cellstr('2017/4/28'),'D8:D8');
        rtn=xlswrite('predict.csv',cellstr('2017/4/28'),'D9:D9');
        rtn=xlswrite('predict.csv',cellstr('2017/4/28'),'D10:D10');       
