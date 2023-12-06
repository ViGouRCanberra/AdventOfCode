#include <iostream>
#include <fstream>
#include <string>

class ColourAndCount
{
    int count, green, blue, red;

    public: 
    ColourAndCount() {
        count = 0;
        green = 0;
        blue = 0;
        red = 0;
    }

    void setGreenCount(int greenCount)
    {
        if (greenCount > green) {
            green = greenCount;
        }
    }

    void setRedCount(int redCount)
    {
        if (red < redCount) {
            red = redCount;
        }
    }

    void setBlueCount(int blueCount)
    {
        if (blue < blueCount) {
            blue = blueCount;
        }
    }

    void setCount(int newCount)
    {
        count = newCount;
    }

    int getGreenCount()
    {
        return green;
    }

    int getRedCount()
    {
        return red;
    }

    int getBlueCount()
    {
        return blue;
    }

    int getCount()
    {
        return count;
    }
};

ColourAndCount processColourAndCount(int count, std::string colour, ColourAndCount colourCount)
{
    switch (colour[0]) {
        case 'g':
            colourCount.setGreenCount(count);
            break;
        case 'b':
            colourCount.setBlueCount(count);
            break;
        case 'r':
            colourCount.setRedCount(count);
            break;
    }

    return colourCount;
}

ColourAndCount getColourAndCount(std::string inputLine, int position, ColourAndCount colourCount) {
    // Continue here counting max green and red and blue
    bool readingCount = true;
    std::string count, colour = "";

    for (int i = position; inputLine[i]; i++) {
        if (readingCount == true) {
            if (inputLine[i] != ' ') {
                count += inputLine[i];
            } else {
                readingCount = false;
            }
        } else { // Read colour
            if (inputLine[i] != ',' and inputLine[i] != ';') {
                colour += inputLine[i];
            } else {
                readingCount = true;
                ++i;

                colourCount = processColourAndCount(stoi(count), colour, colourCount);
                count = "";
                colour = "";
            }
        }
    }

    if (colour != "" and count != "") {
        colourCount = processColourAndCount(stoi(count), colour, colourCount);
    }

    return colourCount;
}

int processLine(std::string inputLine) {
    if (inputLine == "") {
        return 0;
    }

    bool foundColon = false;
    std::string gameNum = "";
    ColourAndCount cAndC;

    for (int i = 5; inputLine[i]; i++) {
        if (inputLine[i] == ':') {
            foundColon = true;
            ++i;
            ++i;
            
            cAndC.setCount(stoi(gameNum));
        }

        if (foundColon == false) {
            gameNum += inputLine[i];
        } else {
            
            cAndC = getColourAndCount(inputLine, i, cAndC);
            break;
        }
    }

    // std::cout << "Game: " << cAndC.getCount() << ", Green: " << cAndC.getGreenCount() << ", Blue: " << cAndC.getBlueCount() << ", Red: " << cAndC.getRedCount() << "\n";

    // PART 1 Compare to rules: (only 12 red cubes, 13 green cubes, and 14 blue cubes)
    // if (cAndC.getGreenCount() <= 13 and cAndC.getBlueCount() <= 14 and cAndC.getRedCount() <= 12) {
    //     return cAndC.getCount();
    // }
    //
    // return 0;

    //Part 2
    return cAndC.getBlueCount() * cAndC.getGreenCount() * cAndC.getRedCount();
}

int main() {
    // std::ifstream inputFile ("test.txt");
    std::ifstream inputFile ("input.txt");
    std::string inputString;

    int total = 0;
    
    if (inputFile.is_open()) {
        while (inputFile) {
            std::getline(inputFile, inputString);
            
            total += processLine(inputString);
        }

        std::cout << "Total: " << total << "\n";
    } else {
        std::cout << "input.txt doesn't exist";
    }

    return 0;
}