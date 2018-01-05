//writtern by Jingxuan Chen
//assisted by Xiuqi Ye
//debugged by Jingxuan Chen
#include<iostream>
#include<cstdlib>
#include<ctime>
#include<math.h>
#include <string>  
#include <vector> 
#include <string.h>
#include <fstream>
#include <stdio.h>
#include <sstream>
using namespace std;
// active function;
double f(double x)
{
	return (1.0 / (1.0 + exp(-x)));
}
//square function
double square(double x)
{
	return x*x;
}
//function of calculation the hidden layer output; 
//this function could be used for first hidden layer and second hidden layer
void cal_h1(double v[5][5], double x[5], double h[5], double b[5])
{
	for (int i = 0; i < 5; i++)
	{
		h[i] = f(v[i][0] * x[0] + v[i][1] * x[1] + v[i][2] * x[2] + v[i][3] * x[3] + v[i][4] * x[4] - b[i]);
	}
}

//function of calculation the output;
void cal_y(double w[5], double h[5], double &y, double b)
{ 
	double tem = 0;
	for (int i = 0; i < 5; i++)
		tem = tem + h[i] * w[i];
	y = f(tem - b);
}
//function of calculating the error;
double cal_error(double y, double t)
{
	return 0.5*square(t - y);
}
//function of the deriaviative of active function;
double f_devition(double y)
{
	return y*(1 - y);
}
void cal_xi2(double xi2[5], double w[5], double t, double y)
{
	for (int i = 0; i < 5; i++)
		xi2[i] = 0;
	for (int m = 0; m < 5; m++)
		xi2[m] = (t - y)*w[m];
}
void cal_xi3(double xi2[5], double u[5][5],double xi3[5])
{
	for (int i = 0; i < 5; i++)
		xi3[i] = 0;
	for (int i = 0; i < 5; i++)
		for (int m = 0; m < 5; m++)
			xi3[i] = xi3[i] + xi2[m] * u[m][i];
}
//update the first layer weight ; this function can use for both first hidden layer and second hidden layer
// x[5] is the input. h1[5] is the output layer v[5][5] is the weight, b1[5] is the bias weight yita is the learing rate
void update_hidden1_weight(double x[5], double h1[5], double v[5][5], double xi3[5], double b1[5],double yita)
{
	for (int i = 0; i < 5; i++)
	{
		b1[i] = b1[i] + xi3[i] * f_devition(h1[i])*(-1)*yita;
		for (int j = 0; j < 5; j++)
			v[i][j] = v[i][j] + yita*x[j] * xi3[i] * f_devition(h1[i]);
	}
}

//update the output weight delta w;
void update_output_weight(double t, double y, double h[5], double w[5], double &b, double yita)
{
	double deltaW[5];
	double delta_b = 0;
	for (int i = 0; i < 5; i++)
		deltaW[i] = (t - y)*f_devition(y)*h[i];
	delta_b = delta_b + (t - y)*f_devition(y)*(-1.0);
	for (int i = 0; i < 5; i++)
		w[i] = w[i] + deltaW[i] * yita;
	b = b + delta_b*yita;
}
//update the hidden layer weight delta v;

string Trim(string& str)
{
	str.erase(0, str.find_first_not_of(" \t\r\n"));
	str.erase(str.find_last_not_of(" \t\r\n") + 1);
	return str;
}
//read data from csv
void read(char filename[], double a[])
{
	ifstream fin(filename);
	string line;
	int i = 0;
	while (getline(fin, line))
	{
		//if (i >= 10)
			//break;
		istringstream sin(line);
		vector<string> fields;
		string field;
		while (getline(sin, field, ','))
		{
			fields.push_back(field);
		}
		string price = Trim(fields[4]);
		//price.erase(0, 1);
		//price.erase(6, 1);
		a[i] = atof(price.c_str());
		i++;
	}
	for (int j = 0; j < i-1; j++)
		a[j] = a[j + 1];
	return;
}
//count the number of data
int countdata(char filename[])
{
	ifstream ReadFile;
	int n = 0;
	string temp;
	ReadFile.open(filename, ios::in);
	if (ReadFile.fail())
		return 0;
	else
	{
		while (getline(ReadFile, temp))
			n++;
		return n;
	}
	ReadFile.close();
}
void find1(double x[], int n, double  &max, double &min)
{
	max = min = x[0];
	for (int i = 1; i < n; i++)
		if (x[i] > max)
			max = x[i];
		else if (x[i] < min)
			min = x[i];
}
void change(double x[], int number, double max, double min)
{
	for (int i = 0; i < number; i++)
		x[i] = (x[i] - min) / (max - min);
}
double training(double x[], int number, double v[5][5], double u[5][5], double w[5], double b1[5], double b2[5], double &b3,double yita)
{
	double in[5], y, t;
	double h1[5], h2[5];
	double xi2[5], xi3[5];
	double error_all = 0;
	double error;
	for (int i = 0; i < number - 5; i++)
	{
		t = x[0];
		for (int j = 0; j < 5; j++)
			in[j] = x[j + 1 + i];
		cal_h1(v, x, h1, b1);
		cal_h1(u, h1, h2, b2);
		cal_y(w, h2, y, b3);
		error = cal_error(y, t);
		//cout << error << "d" << endl;
		error_all = error_all + error;
		cal_xi2(xi2, w, t, y);
		cal_xi3(xi2, u, xi3);
		update_hidden1_weight(in, h1, v, xi3, b1, yita);
		update_hidden1_weight(h1, h2, u, xi2, b2, yita);
		update_output_weight(t, y, h2, w, b3, yita);
	}
	return error_all;
}
void prediction(char filename[], double pre_out[])
{
	double v[5][5], u[5][5], w[5], h1[5], h2[5], y, t, b1[5], b2[5], b3, in[5], max, min;
	double xi2[5], xi3[5];
	double yita, yimixilu, error_all;
	yita = 0.1;
	yimixilu = 1;
	double *x, *weight;
	int tem1, tem2;
	tem1 = countdata(filename);
	x = new double[tem1];
	read(filename, x);
	find1(x, tem1, max, min);
	cout << "max" << max << endl << "min" << min << endl;
	for (int i = 0; i < tem1; i++)
		cout << x[i] << endl;
	change(x, tem1, max, min);
	srand(0);
	//srand(time(0));
	for (int i = 0; i < 5; i++)
	{
		w[i] = 2 * (rand() + 0.0) / RAND_MAX - 1.0;
		b1[i] = 2 * (rand() + 0.0) / RAND_MAX - 1.0;
		b2[i] = 2 * (rand() + 0.0) / RAND_MAX - 1.0;

		for (int j = 0; j < 5; j++)
		{
			v[i][j] = 2 * (rand() + 0.0) / RAND_MAX - 1.0;
			u[i][j] = 2 * (rand() + 0.0) / RAND_MAX - 1.0;
		}
	}
	b3 = 2 * (rand() + 0.0) / RAND_MAX - 1.0;
	int n = 1;
	do {
		error_all = training(x, tem1, v, u, w, b1, b2, b3, yita);
		n++;
		cout << error_all << endl;
	} while (error_all > yimixilu && n < 100);

	double pre_in[5];
	double pre_tem;
	for (int i = 0; i < 5; i++)
	{
		pre_in[i] = x[i];
	}
	for (int i = 0; i < 5; i++)
		cout << "h" << i << pre_in[i] << endl;
	for (int i = 0; i < 7; i++)
	{
		cal_h1(v, pre_in, h1, b1);
		cal_h1(u, h1, h2, b2);
		cal_y(w, h2, pre_tem, b3);
		cout << pre_tem << "aa" << endl;
		for (int j = 0; j < 4; j++)
			pre_in[j] = pre_in[j + 1];
		pre_in[4] = pre_tem;
		//for (int k = 0; k < 5; k++)
		//cout << "t" << pre_in[k] << endl;
		pre_out[i] = pre_tem * (max - min) + min;
		cout << "r" << pre_out[i] << endl;
	} 
}


int main()
{
	double pre_out1[7], pre_out2[7], pre_out3[7], pre_out4[7], pre_out5[7], pre_out6[7], pre_out7[7], pre_out8[7], pre_out9[7], pre_out10[7];
	prediction("./historical/GOOG_historical.csv", pre_out1);
	prediction("./historical/YHOO_historical.csv", pre_out2);
	prediction("./historical/EGT_historical.csv", pre_out3);
	prediction("./historical/AAPL_historical.csv", pre_out4);
	prediction("./historical/IBM_historical.csv", pre_out5);
	prediction("./historical/ISRG_historical.csv", pre_out6);
	prediction("./historical/GE_historical.csv", pre_out7);
	prediction("./historical/MSFT_historical.csv", pre_out8);
	prediction("./historical/PTR_historical.csv", pre_out9);
	prediction("./historical/SNGX_historical.csv", pre_out10);
	
	ofstream file;
	file.open("ANN_Prediction.csv");
	if (!file.is_open())
	{
		cout << "fail to open.";
		return 0;
	}
	file << "1" << "," << "GOOG" << ",";
	for (int i = 0; i < 7; i++)
		file << pre_out1[i] << ",";
	file << endl;
	file << "2" << "," << "YHOO" << ",";
	for (int i = 0; i < 7; i++)
		file << pre_out2[i] << ",";
	file << endl;
	file << "3" << "," << "EGT" << ",";
	for (int i = 0; i < 7; i++)
		file << pre_out3[i] << ",";
	file << endl;
	file << "4" << "," << "AAPL" << ",";
	for (int i = 0; i < 7; i++)
		file << pre_out4[i] << ",";
	file << endl;
	file << "5" << "," << "IBM" << ",";
	for (int i = 0; i < 7; i++)
		file << pre_out5[i] << ",";
	file << endl;
	file << "6" << "," << "ISRG" << ",";
	for (int i = 0; i < 7; i++)
		file << pre_out6[i] << ",";
	file << endl;
	file << "7" << "," << "GE" << ",";
	for (int i = 0; i < 7; i++)
		file << pre_out7[i] << ",";
	file << endl;
	file << "8" << "," << "MSFT" << ",";
	for (int i = 0; i < 7; i++)
		file << pre_out8[i] << ",";
	file << endl;
	file << "9" << "," << "PTR" << ",";
	for (int i = 0; i < 7; i++)
		file << pre_out9[i] << ",";
	file << endl;
	file << "10" << "," << "SNGX" << ",";
	for (int i = 0; i < 7; i++)
		file << pre_out10[i] << ",";
	file << endl;
	file.close();

	return 0;
}
