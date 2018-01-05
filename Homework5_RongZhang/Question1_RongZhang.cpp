//
//Homework 5, Question 1
//Completed by Rong Zhang
//

#include <iostream>
#include <stdlib.h>
#include <math.h>
#include <ctime>
using namespace std;

double f(double x)//function f
{
	return (1/(1 + exp(-x)));
}

double f_d(double x)//function df/dx
{
	return f(x)*(1 - f(x));
}

double bp_calculation(double x[4][2],double t[4], double y[4], double v[2][2], double w[2], double h[4][2], double b[3])
{
	for (int i = 0; i < 4; i++)//update hidden layer h
	{
		h[i][0] = f(v[0][0] * x[i][0] + v[0][1] * x[i][1]-b[0]);
		h[i][1] = f(v[1][0] * x[i][0] + v[1][1] * x[i][1]-b[1]);
	}

	for (int i = 0; i < 4; i++)//update output layer y
	{
		y[i] = f(w[0] * h[i][0] + w[1] * h[i][1]-b[2]);
		//cout << y[i] << endl;
	}

	double error = 0;
	for (int i = 0; i < 4; i++)
		error = error + pow((t[i] - y[i]), 2);//calculate the error

	return error/2;//return the error
}

void update_weights(double x[4][2], double t[4],double y[4], double h[4][2], double w[2], double v[2][2], double eta, double b[3])
{
	double deltav[2][2] = { 0,0,0,0 };
	double deltaw[2] = { 0,0 };

	for (int i = 0; i < 4; i++)//update output layer weights
	{
		deltaw[0] = deltaw[0] + (t[i] - y[i])*f_d(h[i][0] * w[0] + h[i][1] * w[1]-b[2])*h[i][0];
		//cout << deltaw[0] << endl;
		deltaw[1] = deltaw[1] + (t[i] - y[i])*f_d(h[i][0] * w[0] + h[i][1] * w[1]-b[2])*h[i][1];
		//cout << deltaw[1] << endl;
		b[2] = b[2] - eta*(t[i] - y[i])*f_d(h[i][0] * w[0] + h[i][1] * w[1] - b[2]);
	}
	w[0] = w[0] + eta*deltaw[0];
	//cout << w[0] << endl;
	w[1] = w[1] + eta*deltaw[1];

	for (int i = 0; i < 4; i++)//update hidden layer weights
	{
		deltav[0][0] = deltav[0][0]+(t[i] - y[i])*f_d(h[i][0] * w[0] + h[i][1] * w[1] - b[2])*w[0] * f_d(x[i][0] * v[0][0] + x[i][1] * v[0][1]-b[0])*x[i][0];
		deltav[0][1] = deltav[0][1]+(t[i] - y[i])*f_d(h[i][0] * w[0] + h[i][1] * w[1] - b[2])*w[0] * f_d(x[i][0] * v[0][0] + x[i][1] * v[0][1]-b[0])*x[i][1];
		deltav[1][0] = deltav[1][0]+(t[i] - y[i])*f_d(h[i][0] * w[0] + h[i][1] * w[1] - b[2])*w[1] * f_d(x[i][0] * v[1][0] + x[i][1] * v[1][1]-b[1])*x[i][0];
		deltav[1][1] = deltav[1][1]+(t[i] - y[i])*f_d(h[i][0] * w[0] + h[i][1] * w[1] - b[2])*w[1] * f_d(x[i][0] * v[1][0] + x[i][1] * v[1][1]-b[1])*x[i][1];
		b[0] = b[0] - eta*(t[i] - y[i])*f_d(h[i][0] * w[0] + h[i][1] * w[1] - b[2])*w[0] * f_d(x[i][0] * v[0][0] + x[i][1] * v[0][1] - b[0]);
		b[1] = b[1] - eta*(t[i] - y[i])*f_d(h[i][0] * w[0] + h[i][1] * w[1] - b[2])*w[1] * f_d(x[i][0] * v[1][0] + x[i][1] * v[1][1] - b[1]);
	}
	v[0][0] = v[0][0] + eta*deltav[0][0];
	v[0][1] = v[0][1] + eta*deltav[0][1];
	v[1][0] = v[1][0] + eta*deltav[1][0];
	v[1][0] = v[1][1] + eta*deltav[1][1];

	
	//cout << w[1] << endl;
}

int main()
{
	double x[4][2] = { 0,0,0,1,1,0,1,1 };
	double t[4] = { 0,1,1,0 };
	double y[4], h[4][2], eta, epsilon;
	cout << "Please input the learning rate:";
	cin >> eta;
	cout << "Please input the expected error:";
	cin >> epsilon;
	srand(1490367191);//random number generator seed
	cout << "The initial weights are:" << endl;
	double v[2][2], w[2], b[3];
	for (int i = 0; i < 2; i++)
		for (int j = 0; j < 2; j++)
		{
			v[i][j] = 2 * rand() / double(RAND_MAX) - 1;//random initial weights
			cout << "v[" << i << "][" << j << "]=" << v[i][j] << endl;
		}

	for (int k = 0; k < 2; k++)
	{
		w[k] = 2 * rand() / double(RAND_MAX) - 1;//random initial weights
		cout << "w[" << k << "]=" << w[k] << endl;
	}

	for (int i = 0; i < 3; i++)
	{
		b[i] = 2 * rand() / double(RAND_MAX) - 1;//random initial bias
	}

	double error;
	error = bp_calculation(x, t, y, v, w, h, b);//first error
	cout << "The first batch error is " << error << endl;
	int count = 0;
	while (error >= epsilon&&count<100000)
	{
		update_weights(x, t, y, h, w, v, eta,b);//update weights
		error= bp_calculation(x, t, y, v, w, h,b );//calculate new error
		//cout << error << endl;
		count++;
	}

	cout << "The final weights are:" << endl;
	for (int i = 0; i < 2; i++)
		for (int j = 0; j < 2; j++)
		{
			cout << "v[" << i << "][" << j << "]=" << v[i][j] << endl;
		}

	for (int k = 0; k < 2; k++)
	{
		cout << "w[" << k << "]=" << w[k] << endl;
	}

	cout << "The final error is " << error << endl;
	cout << "The total number of batches run is " << count << endl;
    
	return 0;
}