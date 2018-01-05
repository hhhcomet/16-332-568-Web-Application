// written by: Xiuqi Ye
// assisted by: Jingxuan Chen
// debugged by: Xiuqi Ye

#include <yvals.h>
#if (_MSC_VER >= 1600)
#define __STDC_UTF_16__
#endif
#include "mex.h"                                           
#include <engine.h>
#include <iomanip>
#include <string.h>
#include <fstream>
#include <stdio.h>
#include <iostream>
#include <iomanip>
#include <sstream>  
#include <string>  
#include <vector>  

using namespace std;

#pragma comment( lib, "libmx.lib" ) 
#pragma comment( lib, "libmex.lib" )    
#pragma comment( lib, "libeng.lib" )

double prediction(char[],double[10],int M);      //funtion to predict the 11th value
double predictionten(char[], double[10], int M);  //funtion to predict the 10th value
void read(char[], double[10]);      //function to read data from csv file
double chooseM(char[]);        //function to compare errors of different orders and choose the optimum order M
string Trim(string&);

int main() {
	double r1, r2, r3, r4, r5, r6, r7, r8, r9, r10;
	
	r1 = chooseM("./historical/GOOG_historical.csv");
	r2 = chooseM("./historical/YHOO_historical.csv");
	r3 = chooseM("./historical/PTR_historical.csv");
	r4 = chooseM("./historical/MSFT_historical.csv");
	r5 = chooseM("./historical/GE_historical.csv");
	r6 = chooseM("./historical/EGT_historical.csv");
	r7 = chooseM("./historical/AAPL_historical.csv");
	r8 = chooseM("./historical/ISRG_historical.csv");
	r9 = chooseM("./historical/SNGX_historical.csv");
	r10 = chooseM("./historical/IBM_historical.csv");

	// output csv file
	ofstream file;
	file.open("Bayesian_Prediction.csv");
	if (!file.is_open())
	{
		cout << "fail to open.";
		return 0;
	}
	file << "No." << "," << "Symbol" << "," << "predict" << endl;
	file << "1" << "," << "GOOG" << "," << r1 << endl;
	file << "2" << "," << "YHOO" << "," << r2 << endl;
	file << "3" << "," << "PTR" <<  "," << r3 << endl;
	file << "4" << "," << "MSFT" << "," << r4 << endl;
	file << "5" << "," << "GE" << "," << r5 << endl;
	file << "6" << "," << "EGT" << "," << r6 << endl;
	file << "7" << "," << "AAPL" << "," << r7 << endl;
	file << "8" << "," << "ISRG" << "," << r8 << endl;
	file << "9" << "," << "SNGX" << "," << r9 << endl;
	file << "10" << "," << "IBM" << "," << r10 << endl;
	file.close();
	
	return 0;
}   // end function main

double chooseM(char filename[])
{
	cout << filename << endl;
	double a9[10];
	double a10[10];
	double r1[3];
	double r2[3];
	double e[3];
	double a0[10];
	read(filename, a0);
	for (int i = 0; i <= 2; i++)
	{
		r1[i] = prediction(filename, a9, i+2);
		r2[i] = prediction(filename, a10, i+2);
		e[i] = abs(r1[i] - a0[9]);
	}
	if (e[1] <= e[2] && e[1] <= e[0])
	{
		cout << "the optimum M is: " << 3 << endl << "the predict value is: " << r2[1] << endl << endl;
		return r2[1];
	}
	else if (e[0] <= e[2] && e[0] <= e[1])
	{
		cout << "the optimum M is: " << 2 << endl << "the predict value is: " << r2[0] << endl << endl;
		return r2[1];
	}
	else if (e[2] <= e[1] && e[2] <= e[0])
	{
		cout << "the optimum M is: " << 4 << endl << "the predict value is: " << r2[2] << endl << endl;
		return r2[2];
	}

}         //end funtion chooseM

double predictionten(char filename[], double a[10], int M)
{
	double result;
	int i = 0, j = 0;
	int nStatus = 0;
	int N = 9;

	Engine *ep;
	ep = engOpen(NULL);                               //open the matlab engine 
	if (ep == NULL)
	{
		cout << "Error: Not Found"
			<< endl;
		exit(1);
	}
	nStatus = engSetVisible(ep, false);
	if (nStatus != 0)                                              //set matlab window invisible
	{
		cout << "Fail to set matlab invisible" << endl;
		exit(EXIT_FAILURE);
	}

	read(filename, a);

	double n[1] = { N };                              //establish a array which size is 1
	mxArray *m_X;                                    //establish a matrix in matlab
	m_X = mxCreateDoubleMatrix(1, 1, mxREAL);        //set the size of matrix m_X in matlab to be 1*1 
	memcpy((void *)mxGetPr(m_X), (void *)n, sizeof(double) * 1);           //copy the data from n to m_X
	engPutVariable(ep, "n", m_X);                           //set m_X as n in matlab 

	double mm[1] = { M };
	mxArray *m_Y;
	m_Y = mxCreateDoubleMatrix(1, 1, mxREAL);
	memcpy((void *)mxGetPr(m_Y), (void *)mm, sizeof(double) * 1);
	engPutVariable(ep, "mm", m_Y);

	mxArray *A;
	// assume a gets initialized, all values
	A = mxCreateDoubleMatrix(1, N, mxREAL);
	memcpy((void *)mxGetPr(A), (void *)a, sizeof(double)*N);
	engPutVariable(ep, "a", A);

	double I[1] = { 0 };
	mxArray *m_I;
	m_I = mxCreateDoubleMatrix(1, 1, mxREAL);
	memcpy((void *)mxGetPr(m_I), (void *)I, sizeof(double) * 1);
	engPutVariable(ep, "I", m_I);

	double J[1] = { 0 };
	mxArray *m_J;
	m_J = mxCreateDoubleMatrix(1, 1, mxREAL);
	memcpy((void *)mxGetPr(m_J), (void *)J, sizeof(double) * 1);
	engPutVariable(ep, "J", m_J);
	//calling matlab to calculate
	nStatus = engEvalString(ep, "X = [];");
	nStatus = engEvalString(ep, "FX = [];");
	nStatus = engEvalString(ep, "FXN = [];");
	nStatus = engEvalString(ep, "Q = [];");
	nStatus = engEvalString(ep, "W = [];");
	nStatus = engEvalString(ep, "S = [];");
	nStatus = engEvalString(ep, "II = [];");
	nStatus = engEvalString(ep, "E = [];");
	nStatus = engEvalString(ep, "beta = 11.1;");
	nStatus = engEvalString(ep, "alpha = 5 * 10 ^ -3;");
	for (int i = 1; i <= N + 1; i++)
	{
		double I[1] = { i };
		memcpy((void *)mxGetPr(m_I), (void *)I, sizeof(double) * 1);
		engPutVariable(ep, "I", m_I);
		nStatus = engEvalString(ep, "X(I) = I / (n + 1);");
	}
	for (int i = 1; i <= M + 1; i++)
	{
		double I[1] = { i };
		memcpy((void *)mxGetPr(m_I), (void *)I, sizeof(double) * 1);
		engPutVariable(ep, "I", m_I);
		nStatus = engEvalString(ep, "FX(I) = X(n + 1);");
	}
	nStatus = engEvalString(ep, "FX = FX';");
	for (int i = 1; i <= M + 1; i++)
		for (int j = 1; j <= M + 1; j++)
		{
			double I[1] = { i };
			memcpy((void *)mxGetPr(m_I), (void *)I, sizeof(double) * 1);
			engPutVariable(ep, "I", m_I);
			double J[1] = { j };
			memcpy((void *)mxGetPr(m_J), (void *)J, sizeof(double) * 1);
			engPutVariable(ep, "J", m_J);
			nStatus = engEvalString(ep, "W(I, J) = 0;");
			nStatus = engEvalString(ep, "S(I, J) = 0;");
			nStatus = engEvalString(ep, "II(I, J) = 0;");
		}
	for (int i = 1; i <= M + 1; i++)
	{
		double I[1] = { i };
		memcpy((void *)mxGetPr(m_I), (void *)I, sizeof(double) * 1);
		engPutVariable(ep, "I", m_I);
		nStatus = engEvalString(ep, "II(I, I) = 1;");
		nStatus = engEvalString(ep, "E(I) = 0;");
	}
	for (int j = 1; j <= N; j++)
	{
		for (int i = 1; i <= M + 1; i++)
		{
			double I[1] = { i };
			memcpy((void *)mxGetPr(m_I), (void *)I, sizeof(double) * 1);
			engPutVariable(ep, "I", m_I);
			double J[1] = { j };
			memcpy((void *)mxGetPr(m_J), (void *)J, sizeof(double) * 1);
			engPutVariable(ep, "J", m_J);
			nStatus = engEvalString(ep, "FXN(I) = X(J) ^ (I - 1);");
		}
		nStatus = engEvalString(ep, "Q = FXN'*FX';");
		nStatus = engEvalString(ep, "W = W + Q;");
	}
	nStatus = engEvalString(ep, "S = W*beta + alpha*II;");
	nStatus = engEvalString(ep, "S = inv(S);");
	nStatus = engEvalString(ep, "s2 = beta^-1 + FX'*S*FX;");
	for (int j = 1; j <= N; j++)
	{
		for (int i = 1; i <= M + 1; i++)
		{
			double I[1] = { i };
			memcpy((void *)mxGetPr(m_I), (void *)I, sizeof(double) * 1);
			engPutVariable(ep, "I", m_I);
			double J[1] = { j };
			memcpy((void *)mxGetPr(m_J), (void *)J, sizeof(double) * 1);
			engPutVariable(ep, "J", m_J);
			nStatus = engEvalString(ep, "FXN(I) = X(J) ^ (I - 1)*a(J);");
		}
		nStatus = engEvalString(ep, "E = E + FXN;");
	}
	nStatus = engEvalString(ep, "E = E';");
	nStatus = engEvalString(ep, "mx = beta*FX'*S*E;");
	nStatus = engEvalString(ep, "z = mx;");

	double *cresult;
	mxArray *mresult;
	mresult = engGetVariable(ep, "z");                   // get the value of z from matlab and copy it into cresult
	cresult = mxGetPr(mresult);

	result = cresult[0];

	mxDestroyArray(A);                  //release the space
	mxDestroyArray(m_X);
	mxDestroyArray(m_Y);
	mxDestroyArray(m_I);
	mxDestroyArray(m_J);

	return result;
}         //end function predictionten

double prediction(char filename[], double a[10],int M)
{  
	double result;
	int i  = 0, j = 0;
	int nStatus = 0;  
	int N = 10;

	Engine *ep;
	ep = engOpen(NULL);                               //open the matlab engine 
	if (ep == NULL)
	{
		cout << "Error: Not Found"
			<< endl;
		exit(1);
	}
	nStatus = engSetVisible(ep, false);
	if (nStatus != 0)                                              //set matlab window invisible
	{
		cout << "Fail to set matlab invisible" << endl;
		exit(EXIT_FAILURE);
	}


	read(filename, a);
	
	double n[1] = { N };                              //establish a array which size is 1
	mxArray *m_X;                                    //establish a matrix in matlab
	m_X = mxCreateDoubleMatrix(1, 1, mxREAL);        //set the size of matrix m_X in matlab to be 1*1 
	memcpy((void *)mxGetPr(m_X), (void *)n, sizeof(double) * 1);           //copy the data from n to m_X
	engPutVariable(ep, "n", m_X);                           //set m_X as n in matlab 

	double mm[1] = { M };
	mxArray *m_Y;
	m_Y = mxCreateDoubleMatrix(1, 1, mxREAL);
	memcpy((void *)mxGetPr(m_Y), (void *)mm, sizeof(double) * 1);
	engPutVariable(ep, "mm", m_Y);

	mxArray *A;
	// assume a gets initialized, all values
	A = mxCreateDoubleMatrix(1, N, mxREAL);
	memcpy((void *)mxGetPr(A), (void *)a, sizeof(double)*N);
	engPutVariable(ep, "a", A);

	double I[1] = { 0 };
	mxArray *m_I;
	m_I = mxCreateDoubleMatrix(1, 1, mxREAL);
	memcpy((void *)mxGetPr(m_I), (void *)I, sizeof(double) * 1);
	engPutVariable(ep, "I", m_I);

	double J[1] = { 0 };
	mxArray *m_J;
	m_J = mxCreateDoubleMatrix(1, 1, mxREAL);
	memcpy((void *)mxGetPr(m_J), (void *)J, sizeof(double) * 1);
	engPutVariable(ep, "J", m_J);
	                                                     //calling matlab to calculate
	nStatus = engEvalString(ep, "X = [];");
	nStatus = engEvalString(ep, "FX = [];");
	nStatus = engEvalString(ep, "FXN = [];");
	nStatus = engEvalString(ep, "Q = [];");
	nStatus = engEvalString(ep, "W = [];");
	nStatus = engEvalString(ep, "S = [];");
	nStatus = engEvalString(ep, "II = [];");
	nStatus = engEvalString(ep, "E = [];");
	nStatus = engEvalString(ep, "beta = 11.1;");
	nStatus = engEvalString(ep, "alpha = 5 * 10 ^ -3;");
	for (int i = 1; i <= N + 1; i++)
	{
		double I[1] = { i };
		memcpy((void *)mxGetPr(m_I), (void *)I, sizeof(double) * 1);
		engPutVariable(ep, "I", m_I);
		nStatus = engEvalString(ep, "X(I) = I / (n + 1);");
	}
	for (int i = 1; i <= M + 1; i++)
	{
		double I[1] = { i };
		memcpy((void *)mxGetPr(m_I), (void *)I, sizeof(double) * 1);
		engPutVariable(ep, "I", m_I);
		nStatus = engEvalString(ep, "FX(I) = X(n + 1);");
	}
	nStatus = engEvalString(ep, "FX = FX';");
	for (int i = 1; i <= M + 1; i++)
		for (int j = 1; j <= M + 1; j++)
		{
			double I[1] = { i };
			memcpy((void *)mxGetPr(m_I), (void *)I, sizeof(double) * 1);
			engPutVariable(ep, "I", m_I);
			double J[1] = { j };
			memcpy((void *)mxGetPr(m_J), (void *)J, sizeof(double) * 1);
			engPutVariable(ep, "J", m_J);
			nStatus = engEvalString(ep, "W(I, J) = 0;");
			nStatus = engEvalString(ep, "S(I, J) = 0;");
			nStatus = engEvalString(ep, "II(I, J) = 0;");
		}
	for (int i = 1; i <= M + 1; i++)
	{
		double I[1] = { i };
		memcpy((void *)mxGetPr(m_I), (void *)I, sizeof(double) * 1);
		engPutVariable(ep, "I", m_I);
		nStatus = engEvalString(ep, "II(I, I) = 1;");
		nStatus = engEvalString(ep, "E(I) = 0;");
	}
	for (int j = 1; j <= N; j++)
	{
		for (int i = 1; i <= M + 1; i++)
		{
			double I[1] = { i };
			memcpy((void *)mxGetPr(m_I), (void *)I, sizeof(double) * 1);
			engPutVariable(ep, "I", m_I);
			double J[1] = { j };
			memcpy((void *)mxGetPr(m_J), (void *)J, sizeof(double) * 1);
			engPutVariable(ep, "J", m_J);
			nStatus = engEvalString(ep, "FXN(I) = X(J) ^ (I - 1);");
		}
		nStatus = engEvalString(ep, "Q = FXN'*FX';");
		nStatus = engEvalString(ep, "W = W + Q;");
	}
	nStatus = engEvalString(ep, "S = W*beta + alpha*II;");
	nStatus = engEvalString(ep, "S = inv(S);");
	nStatus = engEvalString(ep, "s2 = beta^-1 + FX'*S*FX;");
	for (int j = 1; j <= N; j++)
	{
		for (int i = 1; i <= M + 1; i++)
		{
			double I[1] = { i };
			memcpy((void *)mxGetPr(m_I), (void *)I, sizeof(double) * 1);
			engPutVariable(ep, "I", m_I);
			double J[1] = { j };
			memcpy((void *)mxGetPr(m_J), (void *)J, sizeof(double) * 1);
			engPutVariable(ep, "J", m_J);
			nStatus = engEvalString(ep, "FXN(I) = X(J) ^ (I - 1)*a(J);");
		}
		nStatus = engEvalString(ep, "E = E + FXN;");
	}
	nStatus = engEvalString(ep, "E = E';");
	nStatus = engEvalString(ep, "mx = beta*FX'*S*E;");
	nStatus = engEvalString(ep, "z = mx;");

	double *cresult;
	mxArray *mresult;
	mresult = engGetVariable(ep, "z");                   // get the value of z from matlab and copy it into cresult
		cresult = mxGetPr(mresult);
	
	result = cresult[0];

	mxDestroyArray(A);                  //release the space
	mxDestroyArray(m_X);
	mxDestroyArray(m_Y);
	mxDestroyArray(m_I);
	mxDestroyArray(m_J);

	return result;
}         //end function prediction


void read(char filename[], double a[10])
{
	ifstream fin(filename);
	string line;
	int i = 0;
	while (getline(fin, line))
	{
		if (i >= 10)
			break;
		istringstream sin(line);
		vector<string> fields;
		string field;
		while (getline(sin, field, ','))
		{
			fields.push_back(field);
		}
		string price = Trim(fields[4]);

		a[i] = atof(price.c_str());
		i++;
	}
	return;
}


string Trim(string& str)
{
	str.erase(0, str.find_first_not_of(" \t\r\n"));
	str.erase(str.find_last_not_of(" \t\r\n") + 1);
	return str;
}
