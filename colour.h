#include <stdlib.h>
#include <string.h>
#include <stdio.h>

#define RED 31
#define GREEN 32

void c_default(){
printf("%c[%dm ",0x1B,0);//przywrocenie naturalnych kolorow konsoli
}

void printfc (char *ciag, int colour)
{
printf("%c[%dm %s",0x1B,colour,ciag);
c_default();
}

