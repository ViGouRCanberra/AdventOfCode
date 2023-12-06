// Your First C++ Program

#include <iostream>
#include <fstream>
#include <string>

int checkIfOne(std::string line, int place) {
    if (line[place + 1] != 'n') {
        return 0;
    }
    if (line[place + 2] != 'e') {
        return 0;
    }

    return 1;
}

int checkIfTwoThree(std::string line, int place) {
    std::cout << line[place] << line[place + 1] << line[place + 2] << "\n";

    if (line[place + 1] == 'w' and line[place + 2] == 'o') {
        return 2;
    }

    if (line[place + 1] == 'h' and line[place + 2] == 'r' and line[place + 3] == 'e' and line[place + 4] == 'e') {
        return 3;
    }

    return 0;
}

int checkIfFourFive(std::string line, int place) {
    if (line[place + 1] == 'o' and line[place + 2] == 'u' and line[place + 3] == 'r') {
        return 4;
    }

    if (line[place + 1] == 'i' and line[place + 2] == 'v' and line[place + 3] == 'e') {
        return 5;
    }

    return 0;
}

int checkIfSixSeven(std::string line, int place) {
    if (line[place + 1] == 'i' and line[place + 2] == 'x') {
        return 6;
    }

    if (line[place + 1] == 'e' and line[place + 2] == 'v' and line[place + 3] == 'e' and line[place + 4] == 'n') {
        return 7;
    }


    return 0;
}

int checkIfEight(std::string line, int place) {
    if (line[place + 1] != 'i') {
        return 0;
    }
    if (line[place + 2] != 'g') {
        return 0;
    }
    if (line[place + 3] != 'h') {
        return 0;
    }
    if (line[place + 4] != 't') {
        return 0;
    }

    std::cout << "Is 8 \n";
    return 8;
}

int checkIfNine(std::string line, int place) {
    if (line[place + 1] != 'i') {
        return 0;
    }
    if (line[place + 2] != 'n') {
        return 0;
    }
    if (line[place + 3] != 'e') {
        return 0;
    }

    std::cout << "Is 9 \n";
    return 9;
}

int processLine(std::string inputLine) {
    int i, first, last = 0;
    bool firstFound = false;

    for (i = 0; inputLine[i]; i++) {
        if (isdigit(inputLine.at(i))) {
            if (firstFound == false) {
                first = inputLine[i] - '0';
                firstFound = true;
            }

            last = inputLine[i] - '0';
        } else {
            int possibleDigit = 0;

            switch (inputLine[i]) {
                case 'o':
                    possibleDigit = checkIfOne(inputLine, i);
                    break;
                case 't':
                    possibleDigit = checkIfTwoThree(inputLine, i);
                    break;
                case 'f':
                    possibleDigit = checkIfFourFive(inputLine, i);
                    break;
                case 's':
                    possibleDigit = checkIfSixSeven(inputLine, i);
                    break;
                case 'e':
                    possibleDigit = checkIfEight(inputLine, i);
                    break;
                case 'n':
                    possibleDigit = checkIfNine(inputLine, i);
                    break;
            }

            std::cout << possibleDigit << "\n";

            if (possibleDigit > 0) {
                if (firstFound == false) {
                    first = possibleDigit;
                    firstFound = true;
                }

                last = possibleDigit;
            }
        }
    }

    first *= 10;

    return first + last;
}

int main() {
    // std::ifstream inputFile ("test.txt");
    std::ifstream inputFile ("input.txt");
    std::string inputString;

    int total = 0;
    
    if (inputFile.is_open()) {
        while (inputFile.good()) {
            inputFile >> inputString;
            
            total += processLine(inputString);
        }

        std::cout << total << "\n";
        
    } else {
        std::cout << "input.txt doesn't exist";
    }

    return 0;
}