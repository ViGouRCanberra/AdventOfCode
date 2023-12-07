#include <iostream>
#include <fstream>
#include <string>

// #define ROWS_COLS 10
#define ROWS_COLS 140

void processLine(std::string inputString, char engine[ROWS_COLS][ROWS_COLS], int currentRow)
{
    for (int i = 0; i < ROWS_COLS; i++) {
        engine[currentRow][i] = inputString[i];
    }
}

int getNumLength(char engine[ROWS_COLS][ROWS_COLS], int row, int col)
{
    int length = 0;

    while (isdigit(engine[row][col])) {
        ++length;
        ++col;
    }

    return length;
}

bool checkForSymbol(char engine[ROWS_COLS][ROWS_COLS], int row, int col, int length)
{
    for (int i = -1; i <= 1; i++) {
        for (int j = -1; j <= length; j++) {
            if (row+i == -1 or row+i >= ROWS_COLS or col+j >= ROWS_COLS) {
                break;
            }

            if (col+j <= -1) {
                ++j;
            }

            if (engine[row+i][col+j] != '.' and isdigit(engine[row+i][col+j]) == false) {
                return true;
            }
        }
    }

    return false;
}

int main() {
    // std::ifstream inputFile ("test.txt");
    std::ifstream inputFile ("input.txt");
    std::string inputString;
    
    char engine[ROWS_COLS][ROWS_COLS];
    int total = 0;
    int currentRow = 0;

    if (inputFile.is_open()) {
        while (inputFile) {
            std::getline(inputFile, inputString);
            
            processLine(inputString, engine, currentRow);
            ++currentRow;
        }

        for (int i = 0; i < ROWS_COLS; i++) {
            for (int j = 0; j < ROWS_COLS; j++) {
                if (isdigit(engine[i][j])) {
                    int length = getNumLength(engine, i, j);
                    
                    if (checkForSymbol(engine, i, j, length) == true) {
                        std::string thisNumber = "";

                        for (int k = 0; k < length; k++) {
                            thisNumber += engine[i][j+k];
                        }

                        total += stoi(thisNumber);
                    }

                    j += length;
                }
            }
        }

        std::cout << "Total is: " << total << "\n";

        if (total >= 563793) {
            std::cout << "Answer is too high" << "\n";
        }

        if (total <= 530362) {
            std::cout << "Answer is too low" << "\n";
        }
    } else {
        std::cout << "input.txt doesn't exist";
    }

    return 0;
}