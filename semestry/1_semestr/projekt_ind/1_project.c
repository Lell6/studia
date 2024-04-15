#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <locale.h>

struct points
{
    char point_name[100];
    char achieved;
};

struct quest
{
    char quest_name[100];
    int point_numb;
    struct points point[30];
};
struct quest zadania[20];
int quest_numb;

void mond_c_p(int qf,int pn)
{
    FILE *f_mond;
    FILE *quest;
    char quest_sub_p[50];
    int l,j,i,s,k=0;

    f_mond=fopen("Mondstadt","rb+");
    quest=fopen("quest","rb");

    if(f_mond==NULL)
    {
        printf("Blad otwarcia pliku\n");
        exit(0);
    }
    if(quest==NULL)
    {
        printf("Blad otwarcia pliku\n");
        exit(0);
    }

    fscanf(quest,"%d",&quest_numb);

    printf("\nWybierz podpunkt:\n");

    fgetc(stdin);
    fgets(quest_sub_p,50,stdin);

    for(i=0;i<quest_numb;i++)
    {
        if(i==qf)
        {
            for(j=0;j<pn;j++)
            {
                l=strcmp(zadania[qf].point[j].point_name,quest_sub_p);

                if(l==0)
                {
                    printf("Zmien warunek(X/O):\n");

                    do
                    {
                        s=0;
                        scanf("%c",&zadania[qf].point[j].achieved);

                        int O=(char)(zadania[qf].point[j].achieved);

                        if(O!=79)
                        {
                            int X=(char)(zadania[qf].point[j].achieved);
                            if(X!=88) s++;
                        }
                    }while(s>0);

                    fwrite(&zadania[qf],sizeof(struct quest),1,f_mond);
                    printf("Warunek zostal zmieniony!\n\n");
                }
            }
        }
        else
        {
            k++;
            fwrite(&zadania[i],sizeof(struct quest),1,f_mond);
        }
        if(k==quest_numb-1)
            printf("Blad zmiany\n\n");
    }
    fclose(f_mond);
    fclose(quest);
}

void mond_c_qpn(int qf, int pn)
{
    FILE *f_mond;
    FILE *quest;
    char quest_sub_p[50];
    int l,j,i,s,k=0;

    f_mond=fopen("Mondstadt","rb+");
    quest=fopen("quest","rb");

    if(f_mond==NULL)
    {
        printf("Blad otwarcia pliku\n");
        exit(0);
    }
    if(quest==NULL)
    {
        printf("Blad otwarcia pliku\n");
        exit(0);
    }

    fscanf(quest,"%d",&quest_numb);

    printf("\nZmiana nazwy zadania(1) czy podpunktu(2)?\n");
    scanf("%d",&s);

    if(s==1)
    {
        printf("\n\nPodaj nowa nazwe zadania:\n");

        fgetc(stdin);
        fgets(zadania[qf].quest_name,100,stdin);

        fwrite(&zadania[qf],sizeof(struct quest),1,f_mond);
        printf("Nazwa zadania zostala zmieniona!\n\n");
    }
    else if(s==2)
    {
        printf("\n\nWybierz podpunkt:\n");

        fgetc(stdin);
        fgets(quest_sub_p,100,stdin);

        for(i=0;i<quest_numb;i++)
        {
            if(i==qf)
            {
                for(j=0;j<pn;j++)
                {
                    l=strcmp(zadania[qf].point[j].point_name,quest_sub_p);

                    if(l==0)
                    {
                        printf("Podaj nowa nazwe podpunktu:\n");

                        fgets(zadania[qf].point[i].point_name,100,stdin);

                        fwrite(&zadania[qf],sizeof(struct quest),1,f_mond);
                        printf("Nazwa podpunktu zostala zmieniona!\n");
                    }
                }
            }
            else
            {
                k++;
                fwrite(&zadania[i],sizeof(struct quest),1,f_mond);
            }
        }
        if(k==quest_numb-1)
            printf("\nBlad zmiany\n\n");
    }
    fclose(f_mond);
    fclose(quest);
}

void mond_u(int qf,int pn)
{
    FILE *f_mond;
    FILE *quest;
    int i,j,l,quest_del_i;
    char quest_del[50];

    f_mond=fopen("Mondstadt","rb");
    quest=fopen("quest","rb");

    if(f_mond==NULL)
    {
        printf("Blad otwarcia pliku\n");
        exit(0);
    }
    if(quest==NULL)
    {
        printf("Blad otwarcia pliku\n");
        exit(0);
    }

    fscanf(quest,"%d",&quest_numb);
    quest_del_i=quest_numb;

    fclose(quest);
    quest=fopen("quest","wb");

    fclose(f_mond);
    f_mond=fopen("Mondstadt","wb");

    for(i=0;i<quest_numb;i++)
    {
        if(i==qf)
        {
            --quest_del_i;
            fprintf(quest,"%d",quest_del_i);
        }
        else
        {
            fwrite(&zadania[i],sizeof(struct quest),1,f_mond);
        }
    }
    printf("Zadanie zostalo usuniete!\n");

  fclose(quest);
  fclose(f_mond);
}

void mond_w(int n)
{
    FILE *f_mond;
    FILE *quest;
    int i,j,s;

    f_mond=fopen("Mondstadt","rb+");
    quest=fopen("quest","rb");
    fscanf(quest,"%d",&quest_numb);
    fclose(quest);
    quest=fopen("quest","wb");

    if(f_mond==NULL)
    {
        printf("Blad otwarcia pliku\n");
        exit(0);
    }
    if(quest==NULL)
    {
        printf("Blad otwarcia pliku\n");
        exit(0);
    }


    for(i=0;i<n;i++)
    {
        printf("\nPodaj nazwe zadania:\n");

        fgetc(stdin);
        fgets(zadania[i].quest_name,50,stdin);

        printf("Podaj ilosc punktow:\n");
        scanf("%d",&zadania[i].point_numb);
        printf("\n");

        for(j=0;j<zadania[i].point_numb;j++)
        {
            fseek(f_mond,0,SEEK_END);
            printf("Podaj %d punkt:\n",j+1);

            printf("Podaj nazwe punktu:\n");
            fgetc(stdin);
            fgets(zadania[i].point[j].point_name,100,stdin);
            printf("\n");

            printf("Zrobiony?(X/O)\n");

            do
            {
                s=0;
                scanf("%c",&zadania[i].point[j].achieved);

                int O=(char)(zadania[i].point[j].achieved);

                if(O!=79)
                {
                    int X=(char)(zadania[i].point[j].achieved);
                    if(X!=88) s++;
                }
            }while(s>0);

            printf("\n");
        }
            fwrite(&zadania[i],sizeof(struct quest),1,f_mond);

        if(fwrite!=0)
        {
            printf("Zadanie %d zostalo dodane!\n",i+1);
            quest_numb++;
        }
        else
        {
            printf("Blad dodania!\n",i+1);
        }
    }
    fprintf(quest,"%d",quest_numb);
    fclose(f_mond);
    fclose(quest);
}

void mond_s()
{
    FILE *f_mond;
    FILE *f_quest;
    char quest[50];
    int i,l,j,quest_f,option_p,s=0;

    f_mond=fopen("Mondstadt","rb");
    f_quest=fopen("quest","rb");

    fscanf(f_quest,"%d",&quest_numb);

    if(f_mond==NULL)
    {
        printf("Blad otwarcia pliku\n");
        exit(0);
    }
    if(quest==NULL)
    {
        printf("Blad otwarcia pliku\n");
        exit(0);
    }

    if(quest_numb==0)
    {
        printf("Nie ma zadan w podanym regionie. Sprobuj dodac conajmniej jedno\n\n");
    }
    else
    {
        printf("Zadania, ktore mozna wyszukac:\n\n");

        for(i=0;i<quest_numb;i++)
        {
            fread(zadania[i].quest_name,sizeof(struct quest),1,f_mond);
            printf("- %s",zadania[i].quest_name);
        }

        printf("\n\nPodaj nazwe zadania:\n");

        fgetc(stdin);
        fgets(quest,50,stdin);

        printf("\n");

        for(i=0;i<quest_numb;i++)
        {
            fread(zadania[i].quest_name,sizeof(struct quest),1,f_mond);
            l=strcmp(quest,zadania[i].quest_name);

            if(l==0)
            {
                printf("Zadanie: %s\n",zadania[i].quest_name);
                printf("\tPodpunkty: \n");
                for(j=0;j<zadania[i].point_numb;j++)
                {
                    printf("\t%s - %c\n",zadania[i].point[j].point_name,zadania[i].point[j].achieved);
                }
                quest_f=i;
            }
            else
            {
                s++;
            }
        }

        if(s==quest_numb)
        {
            printf("Nie znaleziono zadania\n\n");
        }
        else
        {
            printf("Trzeba cos zmienic w zadaniu?(1/2)\n");
            scanf("%d",&option_p);

            if(option_p==1)
            {
                printf("1 - Zmien nazwe zadania lub podpunktu\n");
                printf("2 - Zmien warunek w podpunkcie\n");

                scanf("%d",&option_p);

                switch(option_p)
                {
                    case 1:
                        mond_c_qpn(quest_f,zadania[quest_f].point_numb);
                        break;
                    case 2:
                        mond_c_p(quest_f,zadania[quest_f].point_numb);
                        break;
                    default:
                        printf("Zle wybrano\n\n");
                        break;
                }
            }
            option_p=0;

            printf("\nChcesz usunac to zadanie(1/2)\n");
            scanf("%d",&option_p);

            if(option_p==1)
                mond_u(quest_f,zadania[quest_f].point_numb);
        }
    }
    fclose(f_mond);
    fclose(f_quest);
}

void mond_ww()
{
    FILE *f_mond;
    FILE *quest;
    int i,j;

    f_mond=fopen("Mondstadt","rb");
    quest=fopen("quest","rb");
    fscanf(quest,"%d",&quest_numb);

    if(f_mond==NULL)
    {
        printf("Blad otwarcia pliku\n");
        exit(0);
    }
    if(quest==NULL)
    {
        printf("Blad otwarcia pliku\n");
        exit(0);
    }

    if(quest_numb==0)
    {
        printf("Nie ma zadan w podanym regionie. Sprobuj dodac conajmniej jedno\n\n");
    }
    else
    {
        printf("Lista zadan: \n\n");

        for(i=0;i<quest_numb;i++)
        {
            fread(zadania[i].quest_name,sizeof(struct quest),1,f_mond);

            printf("Zadanie %d: %s\n",i+1,zadania[i].quest_name);
            printf("Podpunkty: \n");

            for(j=0;j<zadania[i].point_numb;j++)
            {
                printf("\t%s - %c\n",zadania[i].point[j].point_name,zadania[i].point[j].achieved);
            }
            printf("\n\n");
        }
    }
    fclose(quest);
    fclose(f_mond);
}

void liyu_c_p(int qf,int pn)
{
    FILE *f_liyu;
    FILE *quest_l;
    char quest_sub_p[50];
    int l,j,i,s,k=0;

    f_liyu=fopen("Liyue","rb+");
    quest_l=fopen("quest_l","rb");

    if(f_liyu==NULL)
    {
        printf("Blad otwarcia pliku\n");
        exit(0);
    }
    if(quest_l==NULL)
    {
        printf("Blad otwarcia pliku\n");
        exit(0);
    }

    fscanf(quest_l,"%d",&quest_numb);

    printf("\nWybierz podpunkt:\n");

    fgetc(stdin);
    fgets(quest_sub_p,50,stdin);

    for(i=0;i<quest_numb;i++)
    {
        if(i==qf)
        {
            for(j=0;j<pn;j++)
            {
                l=strcmp(zadania[qf].point[j].point_name,quest_sub_p);

                if(l==0)
                {
                    printf("Zmien warunek(X/O):\n");

                    do
                    {
                        s=0;
                        scanf("%c",&zadania[qf].point[j].achieved);

                        int O=(char)(zadania[qf].point[j].achieved);

                        if(O!=79)
                        {
                            int X=(char)(zadania[qf].point[j].achieved);
                            if(X!=88) s++;
                        }
                    }while(s>0);

                    fwrite(&zadania[qf],sizeof(struct quest),1,f_liyu);
                    printf("Warunek zostal zmieniony!\n\n");
                }
            }
        }
        else
        {
            k++;
            fwrite(&zadania[i],sizeof(struct quest),1,f_liyu);
        }
        if(k==quest_numb-1)
            printf("Blad zmiany\n\n");
    }
    fclose(f_liyu);
    fclose(quest_l);
}

void liyu_c_qpn(int qf, int pn)
{
    FILE *f_liyu;
    FILE *quest_l;
    char quest_sub_p[50];
    int l,j,i,s,k=0;

    f_liyu=fopen("Liyue","rb+");
    quest_l=fopen("quest_l","rb");

    if(f_liyu==NULL)
    {
        printf("Blad otwarcia pliku\n");
        exit(0);
    }
    if(quest_l==NULL)
    {
        printf("Blad otwarcia pliku\n");
        exit(0);
    }

    fscanf(quest_l,"%d",&quest_numb);

    printf("\nZmiana nazwy zadania(1) czy podpunktu(2)?\n");
    scanf("%d",&s);

    if(s==1)
    {
        printf("\n\nPodaj nowa nazwe zadania:\n");

        fgetc(stdin);
        fgets(zadania[qf].quest_name,100,stdin);

        fwrite(&zadania[qf],sizeof(struct quest),1,f_liyu);
        printf("Nazwa zadania zostala zmieniona!\n\n");
    }
    else if(s==2)
    {
        printf("\n\nWybierz podpunkt:\n");

        fgetc(stdin);
        fgets(quest_sub_p,100,stdin);

        for(i=0;i<quest_numb;i++)
        {
            if(i==qf)
            {
                for(j=0;j<pn;j++)
                {
                    l=strcmp(zadania[qf].point[j].point_name,quest_sub_p);

                    if(l==0)
                    {
                        printf("Podaj nowa nazwe podpunktu:\n");

                        fgets(zadania[qf].point[i].point_name,100,stdin);

                        fwrite(&zadania[qf],sizeof(struct quest),1,f_liyu);
                        printf("Nazwa podpunktu zostala zmieniona!\n");
                    }
                }
            }
            else
            {
                k++;
                fwrite(&zadania[i],sizeof(struct quest),1,f_liyu);
            }
        }
        if(k==quest_numb-1)
            printf("\nBlad zmiany\n\n");
    }
    fclose(f_liyu);
    fclose(quest_l);
}

void liyu_u(int qf,int pn)
{
    FILE *f_liyu;
    FILE *quest_l;
    int i,j,l,quest_del_i;
    char quest_del[50];

    f_liyu=fopen("Liyue","rb");
    quest_l=fopen("quest_l","rb");

    if(f_liyu==NULL)
    {
        printf("Blad otwarcia pliku\n");
        exit(0);
    }
    if(quest_l==NULL)
    {
        printf("Blad otwarcia pliku\n");
        exit(0);
    }

    fscanf(quest_l,"%d",&quest_numb);
    quest_del_i=quest_numb;

    fclose(quest_l);
    quest_l=fopen("quest_l","wb");

    fclose(f_liyu);
    f_liyu=fopen("Liyue","wb");

    for(i=0;i<quest_numb;i++)
    {
        if(i==qf)
        {
            --quest_del_i;
            fprintf(quest_l,"%d",quest_del_i);
        }
        else
        {
            fwrite(&zadania[i],sizeof(struct quest),1,f_liyu);
        }
    }
    printf("Zadanie zostalo usuniete!\n");

  fclose(quest_l);
  fclose(f_liyu);
}

void liyu_w(int n)
{
    FILE *f_liyu;
    FILE *quest_l;
    int i,j,s;

    f_liyu=fopen("Liyue","rb+");
    quest_l=fopen("quest_l","rb");
    fscanf(quest_l,"%d",&quest_numb);
    fclose(quest_l);
    quest_l=fopen("quest_l","wb");

    if(f_liyu==NULL)
    {
        printf("Blad otwarcia pliku\n");
        exit(0);
    }
    if(quest_l==NULL)
    {
        printf("Blad otwarcia pliku\n");
        exit(0);
    }

    for(i=0;i<n;i++)
    {
        printf("\nPodaj nazwe zadania:\n");

        fgetc(stdin);
        fgets(zadania[i].quest_name,50,stdin);

        printf("Podaj ilosc punktow:\n");
        scanf("%d",&zadania[i].point_numb);
        printf("\n");

        for(j=0;j<zadania[i].point_numb;j++)
        {
            fseek(f_liyu,0,SEEK_END);
            printf("Podaj %d punkt:\n",j+1);

            printf("Podaj nazwe punktu:\n");
            fgetc(stdin);
            fgets(zadania[i].point[j].point_name,50,stdin);
            printf("\n");

            printf("Zrobiony?(X/O)\n");

            do
            {
                s=0;
                scanf("%c",&zadania[i].point[j].achieved);

                int O=(char)(zadania[i].point[j].achieved);

                if(O!=79)
                {
                    int X=(char)(zadania[i].point[j].achieved);
                    if(X!=88) s++;
                }
            }while(s>0);

            printf("\n");
        }
            fwrite(&zadania[i],sizeof(struct quest),1,f_liyu);

        if(fwrite!=0)
        {
            printf("Zadanie %d zostalo dodane!\n",i+1);
            quest_numb++;
        }
        else
        {
            printf("Blad dodania!\n",i+1);
        }
    }
    fprintf(quest_l,"%d",quest_numb);
    fclose(f_liyu);
    fclose(quest_l);
}

void liyu_s()
{
    FILE *f_liyu;
    FILE *f_quest_l;
    char quest[50];
    int i,l,j,quest_f,option_p,s=0;

    f_liyu=fopen("Liyue","rb");
    f_quest_l=fopen("quest_l","rb");

    fscanf(f_quest_l,"%d",&quest_numb);

    if(f_liyu==NULL)
    {
        printf("Blad otwarcia pliku\n");
        exit(0);
    }
    if(f_quest_l==NULL)
    {
        printf("Blad otwarcia pliku\n");
        exit(0);
    }

    if(quest_numb==0)
    {
        printf("Nie ma zadan w podanym regionie. Sprobuj dodac conajmniej jedno\n\n");
    }
    else
    {
        printf("Zadania, ktore mozna wyszukac:\n\n");

        for(i=0;i<quest_numb;i++)
        {
            fread(zadania[i].quest_name,sizeof(struct quest),1,f_liyu);
            printf("- %s",zadania[i].quest_name);
        }

        printf("\nPodaj nazwe zadania:\n");

        fgetc(stdin);
        fgets(quest,50,stdin);

        printf("\n");

        for(i=0;i<quest_numb;i++)
        {
            fread(zadania[i].quest_name,sizeof(struct quest),1,f_liyu);
            l=strcmp(quest,zadania[i].quest_name);

            if(l==0)
            {
                printf("Zadanie: %s\n",zadania[i].quest_name);
                printf("\tPodpunkty: \n");
                for(j=0;j<zadania[i].point_numb;j++)
                {
                    printf("\t%s - %c\n",zadania[i].point[j].point_name,zadania[i].point[j].achieved);
                }
                quest_f=i;
            }
            else
            {
                s++;
            }
        }

        if(s==quest_numb)
        {
            printf("Nie znaleziono zadania\n\n");
        }
        else
        {
            printf("Trzeba cos zmienic w zadaniu?(1/2)\n");
            scanf("%d",&option_p);

            if(option_p==1)
            {
                printf("1 - Zmien nazwe zadania lub podpunktu\n");
                printf("2 - Zmien warunek w podpunkcie\n");

                scanf("%d",&option_p);

                switch(option_p)
                {
                    case 1:
                        liyu_c_qpn(quest_f,zadania[quest_f].point_numb);
                        break;
                    case 2:
                        liyu_c_p(quest_f,zadania[quest_f].point_numb);
                        break;
                    default:
                        printf("Zle wybrano\n\n");
                        break;
                }
            }
            option_p=0;

            printf("\nChcesz usunac to zadanie(1/2)\n");
            scanf("%d",&option_p);

            if(option_p==1)
                liyu_u(quest_f,zadania[quest_f].point_numb);
        }
    }

    fclose(f_liyu);
    fclose(f_quest_l);
}

void liyu_ww()
{
    FILE *f_liyu;
    FILE *quest_l;
    int i,j;

    f_liyu=fopen("Liyue","rb");
    quest_l=fopen("quest_l","rb");
    fscanf(quest_l,"%d",&quest_numb);

    if(f_liyu==NULL)
    {
        printf("Blad otwarcia pliku\n");
        exit(0);
    }
    if(quest_l==NULL)
    {
        printf("Blad otwarcia pliku\n");
        exit(0);
    }

    if(quest_numb==0)
    {
        printf("Nie ma zadan w podanym regionie. Sprobuj dodac conajmniej jedno\n\n");
    }
    else
    {
        printf("Lista zadan: \n\n");

        for(i=0;i<quest_numb;i++)
        {
            fread(zadania[i].quest_name,sizeof(struct quest),1,f_liyu);

            printf("Zadanie %d: %s\n",i+1,zadania[i].quest_name);
            printf("Podpunkty: \n");

            for(j=0;j<zadania[i].point_numb;j++)
            {
                printf("\t%s - %c\n",zadania[i].point[j].point_name,zadania[i].point[j].achieved);
            }
            printf("\n\n");
        }
    }

    fclose(quest_l);
    fclose(f_liyu);
}

void inaz_c_p(int qf,int pn)
{
    FILE *f_inaz;
    FILE *quest_i;
    char quest_sub_p[50];
    int l,j,i,s,k=0;

    f_inaz=fopen("Inazuma","rb+");
    quest_i=fopen("quest_i","rb");

    if(f_inaz==NULL)
    {
        printf("Blad otwarcia pliku\n");
        exit(0);
    }
    if(quest_i==NULL)
    {
        printf("Blad otwarcia pliku\n");
        exit(0);
    }

    fscanf(quest_i,"%d",&quest_numb);

    printf("\nWybierz podpunkt:\n");

    fgetc(stdin);
    fgets(quest_sub_p,50,stdin);

    for(i=0;i<quest_numb;i++)
    {
        if(i==qf)
        {
            for(j=0;j<pn;j++)
            {
                l=strcmp(zadania[qf].point[j].point_name,quest_sub_p);

                if(l==0)
                {
                    printf("Zmien warunek(X/O):\n");

                    do
                    {
                        s=0;
                        scanf("%c",&zadania[qf].point[j].achieved);

                        int O=(char)(zadania[qf].point[j].achieved);

                        if(O!=79)
                        {
                            int X=(char)(zadania[qf].point[j].achieved);
                            if(X!=88) s++;
                        }
                    }while(s>0);

                    fwrite(&zadania[qf],sizeof(struct quest),1,f_inaz);
                    printf("Warunek zostal zmieniony!\n\n");
                }
            }
        }
        else
        {
            k++;
            fwrite(&zadania[i],sizeof(struct quest),1,f_inaz);
        }
        if(k==quest_numb-1);
            printf("Blad zmiany\n\n");
    }
    fclose(f_inaz);
    fclose(quest_i);
}

void inaz_c_qpn(int qf, int pn)
{
    FILE *f_inaz;
    FILE *quest_i;
    char quest_sub_p[50];
    int l,j,i,s,k=0;

    f_inaz=fopen("Inazuma","rb+");
    quest_i=fopen("quest_i","rb");

    if(f_inaz==NULL)
    {
        printf("Blad otwarcia pliku\n");
        exit(0);
    }
    if(quest_i==NULL)
    {
        printf("Blad otwarcia pliku\n");
        exit(0);
    }

    fscanf(quest_i,"%d",&quest_numb);

    printf("\nZmiana nazwy zadania(1) czy podpunktu(2)?\n");
    scanf("%d",&s);

    if(s==1)
    {
        printf("\n\nPodaj nowa nazwe zadania:\n");

        fgetc(stdin);
        fgets(zadania[qf].quest_name,100,stdin);

        fwrite(&zadania[qf],sizeof(struct quest),1,f_inaz);
        printf("Nazwa zadania zostala zmieniona!\n\n");
    }
    else if(s==2)
    {
        printf("\n\nWybierz podpunkt:\n");

        fgetc(stdin);
        fgets(quest_sub_p,100,stdin);

        for(i=0;i<quest_numb;i++)
        {
            if(i==qf)
            {
                for(j=0;j<pn;j++)
                {
                    l=strcmp(zadania[qf].point[j].point_name,quest_sub_p);

                    if(l==0)
                    {
                        printf("Podaj nowa nazwe podpunktu:\n");

                        fgets(zadania[qf].point[i].point_name,100,stdin);

                        fwrite(&zadania[qf],sizeof(struct quest),1,f_inaz);
                        printf("Nazwa podpunktu zostala zmieniona!\n");
                    }
                }
            }
            else
            {
                k++;
                fwrite(&zadania[i],sizeof(struct quest),1,f_inaz);
            }
        }
        if(k==quest_numb-1)
            printf("\nBlad zmiany\n\n");
    }
    fclose(f_inaz);
    fclose(quest_i);
}

void inaz_u(int qf,int pn)
{
    FILE *f_inaz;
    FILE *quest_i;
    int i,j,l,quest_del_i;
    char quest_del[50];

    f_inaz=fopen("Inazuma","rb");
    quest_i=fopen("quest_i","rb");

    if(f_inaz==NULL)
    {
        printf("Blad otwarcia pliku\n");
        exit(0);
    }
    if(quest_i==NULL)
    {
        printf("blad otwarcia pliku\n");
        exit(0);
    }

    fscanf(quest_i,"%d",&quest_numb);
    quest_del_i=quest_numb;

    fclose(quest_i);
    quest_i=fopen("quest_i","wb");

    fclose(f_inaz);
    f_inaz=fopen("Inazuma","wb");

    for(i=0;i<quest_numb;i++)
    {
        if(i==qf)
        {
            --quest_del_i;
            fprintf(quest_i,"%d",quest_del_i);
        }
        else
        {
            fwrite(&zadania[i],sizeof(struct quest),1,f_inaz);
        }
    }
    printf("Zadanie zostalo usuniete!\n");

  fclose(quest_i);
  fclose(f_inaz);
}

void inaz_w(int n)
{
    FILE *f_inaz;
    FILE *quest_i;
    int i,j,s;

    f_inaz=fopen("Inazuma","rb+");
    quest_i=fopen("quest_i","rb");
    fscanf(quest_i,"%d",&quest_numb);
    fclose(quest_i);
    quest_i=fopen("quest_i","wb");

    if(f_inaz==NULL)
    {
        printf("Blad otwarcia pliku\n");
        exit(0);
    }
    if(quest_i==NULL)
    {
        printf("blad otwarcia pliku\n");
        exit(0);
    }

    for(i=0;i<n;i++)
    {
        printf("\nPodaj nazwe zadania:\n");

        fgetc(stdin);
        fgets(zadania[i].quest_name,50,stdin);

        printf("Podaj ilosc punktow:\n");
        scanf("%d",&zadania[i].point_numb);
        printf("\n");

        for(j=0;j<zadania[i].point_numb;j++)
        {
            fseek(f_inaz,0,SEEK_END);
            printf("Podaj %d punkt:\n",j+1);

            printf("Podaj nazwe punktu:\n");
            fgetc(stdin);
            fgets(zadania[i].point[j].point_name,50,stdin);
            printf("\n");

            printf("Zrobiony(X/O)?\n");

            do
            {
                s=0;
                scanf("%c",&zadania[i].point[j].achieved);

                int O=(char)(zadania[i].point[j].achieved);

                if(O!=79)
                {
                    int X=(char)(zadania[i].point[j].achieved);
                    if(X!=88) s++;
                }
            }while(s>0);

            printf("\n");
        }
            fwrite(&zadania[i],sizeof(struct quest),1,f_inaz);

        if(fwrite!=0)
        {
            printf("Zadanie %d zostalo dodane!\n",i+1);
            quest_numb++;
        }
        else
        {
            printf("Blad dodania!\n",i+1);
        }
    }
    fprintf(quest_i,"%d",quest_numb);
    fclose(f_inaz);
    fclose(quest_i);
}

void inaz_s()
{
    FILE *f_inaz;
    FILE *f_quest_i;
    char quest[50];
    int i,l,j,quest_f,option_p,s=0;

    f_inaz=fopen("Inazuma","rb");
    f_quest_i=fopen("quest_i","rb");

    fscanf(f_quest_i,"%d",&quest_numb);

    if(f_inaz==NULL)
    {
        printf("Blad otwarcia pliku\n");
        exit(0);
    }
    if(f_quest_i==NULL)
    {
        printf("Blad otwarcia pliku\n");
        exit(0);
    }

    if(quest_numb==0)
    {
        printf("Nie ma zadan w podanym regionie. Sprobuj dodac conajmniej jedno\n\n");
    }
    else
    {
        printf("Zadania, ktore mozna wyszukac:\n\n");

        for(i=0;i<quest_numb;i++)
        {
            fread(zadania[i].quest_name,sizeof(struct quest),1,f_inaz);
            printf("- %s",zadania[i].quest_name);
        }

        printf("\nPodaj nazwe zadania:\n");

        fgetc(stdin);
        fgets(quest,50,stdin);

        printf("\n");

        for(i=0;i<quest_numb;i++)
        {
            fread(zadania[i].quest_name,sizeof(struct quest),1,f_inaz);
            l=strcmp(quest,zadania[i].quest_name);

            if(l==0)
            {
                printf("Zadanie: %s\n",zadania[i].quest_name);
                printf("\tPodpunkty: \n");
                for(j=0;j<zadania[i].point_numb;j++)
                {
                    printf("\t%s - %c\n",zadania[i].point[j].point_name,zadania[i].point[j].achieved);
                }
                quest_f=i;
            }
            else
            {
                s++;
            }
        }

        if(s==quest_numb)
        {
            printf("Nie znaleziono zadania\n\n");
        }
        else
        {
            printf("Trzeba cos zmienic w zadaniu?(1/2)\n");
            scanf("%d",&option_p);

            if(option_p==1)
            {
                printf("1 - Zmien nazwe zadania lub podpunktu\n");
                printf("2 - Zmien warunek w podpunkcie\n");

                scanf("%d",&option_p);

                switch(option_p)
                {
                    case 1:
                        inaz_c_qpn(quest_f,zadania[quest_f].point_numb);
                        break;
                    case 2:
                        inaz_c_p(quest_f,zadania[quest_f].point_numb);
                        break;
                    default:
                        printf("Zle wybrano\n\n");
                        break;
                }
            }
            option_p=0;

            printf("\nChcesz usunac to zadanie(1/2)\n");
            scanf("%d",&option_p);

            if(option_p==1)
                inaz_u(quest_f,zadania[quest_f].point_numb);
        }
    }

    fclose(f_inaz);
    fclose(f_quest_i);
}

void inaz_ww()
{
    FILE *f_inaz;
    FILE *quest_i;
    int i,j;

    f_inaz=fopen("Inazuma","rb");
    quest_i=fopen("quest_i","rb");
    fscanf(quest_i,"%d",&quest_numb);

    if(f_inaz==NULL)
    {
        printf("Blad otwarcia pliku\n");
        exit(0);
    }
    if(quest_i==NULL)
    {
        printf("blad otwarcia pliku\n");
        exit(0);
    }

    if(quest_numb==0)
    {
        printf("Nie ma zadan w podanym regionie. Sprobuj dodac conajmniej jedno\n\n");
    }
    else
    {
        printf("Lista zadan: \n\n");

        for(i=0;i<quest_numb;i++)
        {
            fread(zadania[i].quest_name,sizeof(struct quest),1,f_inaz);

            printf("Zadanie %d: %s\n",i+1,zadania[i].quest_name);
            printf("Podpunkty: \n");

            for(j=0;j<zadania[i].point_numb;j++)
            {
                printf("\t%s - %c\n",zadania[i].point[j].point_name,zadania[i].point[j].achieved);
            }
            printf("\n\n");
        }
    }
    fclose(quest_i);
    fclose(f_inaz);
}

void sumr_c_p(int qf,int pn)
{
    FILE *f_sumr;
    FILE *quest_s;
    char quest_sub_p[50];
    int l,j,i,s,k=0;

    f_sumr=fopen("Sumeru","rb+");
    quest_s=fopen("quest_s","rb");

    if(f_sumr==NULL)
    {
        printf("Blad otwarcia pliku\n");
        exit(0);
    }
    if(quest_s==NULL)
    {
        printf("Blad otwarcia pliku\n");
        exit(0);
    }

    fscanf(quest_s,"%d",&quest_numb);

    printf("\nWybierz podpunkt:\n");

    fgetc(stdin);
    fgets(quest_sub_p,50,stdin);

    for(i=0;i<quest_numb;i++)
    {
        if(i==qf)
        {
            for(j=0;j<pn;j++)
            {
                l=strcmp(zadania[qf].point[j].point_name,quest_sub_p);

                if(l==0)
                {
                    printf("Zmien warunek(X/O):\n");

                    do
                    {
                        s=0;
                        scanf("%c",&zadania[qf].point[j].achieved);

                        int O=(char)(zadania[qf].point[j].achieved);

                        if(O!=79)
                        {
                            int X=(char)(zadania[qf].point[j].achieved);
                            if(X!=88) s++;
                        }
                    }while(s>0);

                    fwrite(&zadania[qf],sizeof(struct quest),1,f_sumr);
                    printf("Warunek zostal zmieniony!\n\n");
                }
            }
        }
        else
        {
            k++;
            fwrite(&zadania[i],sizeof(struct quest),1,f_sumr);
        }
        if(k==quest_numb-1);
    }
    fclose(f_sumr);
    fclose(quest_s);
}

void sumr_c_qpn(int qf, int pn)
{
    FILE *f_sumr;
    FILE *quest_s;
    char quest_sub_p[50];
    int l,j,i,s,k=0;

    f_sumr=fopen("Sumeru","rb+");
    quest_s=fopen("quest_s","rb");

    if(f_sumr==NULL)
    {
        printf("Blad otwarcia pliku\n");
        exit(0);
    }
    if(quest_s==NULL)
    {
        printf("Blad otwarcia pliku\n");
        exit(0);
    }

    fscanf(quest_s,"%d",&quest_numb);

    printf("\nZmiana nazwy zadania(1) czy podpunktu(2)?\n");
    scanf("%d",&s);

    if(s==1)
    {
        printf("\n\nPodaj nowa nazwe zadania:\n");

        fgetc(stdin);
        fgets(zadania[qf].quest_name,100,stdin);

        fwrite(&zadania[qf],sizeof(struct quest),1,f_sumr);
        printf("Nazwa zadania zostala zmieniona!\n\n");
    }
    else if(s==2)
    {
        printf("\n\nWybierz podpunkt:\n");

        fgetc(stdin);
        fgets(quest_sub_p,100,stdin);

        for(i=0;i<quest_numb;i++)
        {
            if(i==qf)
            {
                for(j=0;j<pn;j++)
                {
                    l=strcmp(zadania[qf].point[j].point_name,quest_sub_p);

                    if(l==0)
                    {
                        printf("Podaj nowa nazwe podpunktu:\n");

                        fgets(zadania[qf].point[i].point_name,100,stdin);

                        fwrite(&zadania[qf],sizeof(struct quest),1,f_sumr);
                        printf("Nazwa podpunktu zostala zmieniona!\n");
                    }
                }
            }
            else
            {
                fwrite(&zadania[i],sizeof(struct quest),1,f_sumr);
            }
        }
        if(k==quest_numb-1)
            printf("\nBlad zmiany\n\n");
    }
    fclose(f_sumr);
    fclose(quest_s);
}

void sumr_u(int qf,int pn)
{
    FILE *f_sumr;
    FILE *quest_s;
    int i,j,l,quest_del_i;
    char quest_del[50];

    f_sumr=fopen("Sumeru","rb");
    quest_s=fopen("quest_s","rb");

    if(f_sumr==NULL)
    {
        printf("Blad otwarcia pliku\n");
        exit(0);
    }
    if(quest_s==NULL)
    {
        printf("blad otwarcia pliku\n");
        exit(0);
    }

    fscanf(quest_s,"%d",&quest_numb);
    quest_del_i=quest_numb;

    fclose(quest_s);
    quest_s=fopen("quest_s","wb");

    fclose(f_sumr);
    f_sumr=fopen("Sumeru","wb");

    for(i=0;i<quest_numb;i++)
    {
        if(i==qf)
        {
            --quest_del_i;
            fprintf(quest_s,"%d",quest_del_i);
        }
        else
        {
            fwrite(&zadania[i],sizeof(struct quest),1,f_sumr);
        }
    }
    printf("Zadanie zostalo usuniete!\n");

  fclose(quest_s);
  fclose(f_sumr);
}

void sumr_w(int n)
{
    FILE *f_sumr;
    FILE *quest_s;
    int i,j,s;

    f_sumr=fopen("Sumeru","rb+");
    quest_s=fopen("quest_s","rb");
    fscanf(quest_s,"%d",&quest_numb);
    fclose(quest_s);
    quest_s=fopen("quest_s","wb");

    if(f_sumr==NULL)
    {
        printf("Blad otwarcia pliku\n");
        exit(0);
    }
    if(quest_s==NULL)
    {
        printf("Blad otwarcia pliku\n");
        exit(0);
    }

    for(i=0;i<n;i++)
    {
        printf("\nPodaj nazwe zadania:\n");

        fgetc(stdin);
        fgets(zadania[i].quest_name,50,stdin);

        printf("Podaj ilosc punktow:\n");
        scanf("%d",&zadania[i].point_numb);
        printf("\n");

        for(j=0;j<zadania[i].point_numb;j++)
        {
            fseek(f_sumr,0,SEEK_END);
            printf("Podaj %d punkt:\n",j+1);

            printf("Podaj nazwe punktu:\n");
            fgetc(stdin);
            fgets(zadania[i].point[j].point_name,50,stdin);
            printf("\n");

            printf("Zrobiony(X/O)?\n");

            do
            {
                s=0;
                scanf("%c",&zadania[i].point[j].achieved);

                int O=(char)(zadania[i].point[j].achieved);

                if(O!=79)
                {
                    int X=(char)(zadania[i].point[j].achieved);
                    if(X!=88) s++;
                }
            }while(s>0);

            printf("\n");
        }
            fwrite(&zadania[i],sizeof(struct quest),1,f_sumr);

        if(fwrite!=0)
        {
            printf("Zadanie %d zostalo dodane!\n",i+1);
            quest_numb++;
        }
        else
        {
            printf("Blad dodania!\n",i+1);
        }
    }
    fprintf(quest_s,"%d",quest_numb);
    fclose(f_sumr);
    fclose(quest_s);
}

void sumr_s()
{
    FILE *f_sumr;
    FILE *f_quest_s;
    char quest[50];
    int i,l,j,quest_f,option_p,s=0;

    f_sumr=fopen("Sumeru","rb");
    f_quest_s=fopen("quest_s","rb");

    fscanf(f_quest_s,"%d",&quest_numb);

    if(f_sumr==NULL)
    {
        printf("Blad otwarcia pliku\n");
        exit(0);
    }
    if(f_quest_s==NULL)
    {
        printf("Blad otwarcia pliku\n");
        exit(0);
    }

    if(quest_numb==0)
    {
        printf("Nie ma zadan w podanym regionie. Sprobuj dodac conajmniej jedno\n\n");
    }
    else
    {
        printf("Zadania, ktore mozna wyszukac:\n\n");

        for(i=0;i<quest_numb;i++)
        {
            fread(zadania[i].quest_name,sizeof(struct quest),1,f_sumr);
            printf("- %s",zadania[i].quest_name);
        }

        printf("\nPodaj nazwe zadania:\n");

        fgetc(stdin);
        fgets(quest,50,stdin);

        printf("\n");

        for(i=0;i<quest_numb;i++)
        {
            fread(zadania[i].quest_name,sizeof(struct quest),1,f_sumr);
            l=strcmp(quest,zadania[i].quest_name);

            if(l==0)
            {
                printf("Zadanie: %s\n",zadania[i].quest_name);
                printf("\tPodpunkty: \n");
                for(j=0;j<zadania[i].point_numb;j++)
                {
                    printf("\t%s - %c\n",zadania[i].point[j].point_name,zadania[i].point[j].achieved);
                }
                quest_f=i;
            }
            else
            {
                s++;
            }
        }

        if(s==quest_numb)
        {
            printf("Nie znaleziono zadania\n\n");
        }
        else
        {
            printf("Trzeba cos zmienic w zadaniu?(1/2)\n");
            scanf("%d",&option_p);

            if(option_p==1)
            {
                printf("1 - Zmien nazwe zadania lub podpunktu\n");
                printf("2 - Zmien warunek w podpunkcie\n");

                scanf("%d",&option_p);

                switch(option_p)
                {
                    case 1:
                        sumr_c_qpn(quest_f,zadania[quest_f].point_numb);
                        break;
                    case 2:
                        sumr_c_p(quest_f,zadania[quest_f].point_numb);
                        break;
                    default:
                        printf("Zle wybrano\n\n");
                        break;
                }
            }
            option_p=0;

            printf("\nChcesz usunac to zadanie(1/2)\n");
            scanf("%d",&option_p);

            if(option_p==1)
                sumr_u(quest_f,zadania[quest_f].point_numb);
        }
    }

    fclose(f_sumr);
    fclose(f_quest_s);
}

void sumr_ww()
{
    FILE *f_sumr;
    FILE *quest_s;
    int i,j;

    f_sumr=fopen("Sumeru","rb");
    quest_s=fopen("quest_s","rb");
    fscanf(quest_s,"%d",&quest_numb);

    if(f_sumr==NULL)
    {
        printf("Blad otwarcia pliku\n");
        exit(0);
    }
    if(quest_s==NULL)
    {
        printf("blad otwarcia pliku\n");
        exit(0);
    }

    if(quest_numb==0)
    {
        printf("Nie ma zadan w podanym regionie. Sprobuj dodac conajmniej jedno\n\n");
    }
    else
    {
        printf("Lista zadan: \n\n");

        for(i=0;i<quest_numb;i++)
        {
            fread(zadania[i].quest_name,sizeof(struct quest),1,f_sumr);

            printf("Zadanie %d: %s\n",i+1,zadania[i].quest_name);
            printf("Podpunkty: \n");

            for(j=0;j<zadania[i].point_numb;j++)
            {
                printf("\t%s - %c\n",zadania[i].point[j].point_name,zadania[i].point[j].achieved);
            }
            printf("\n\n");
        }
    }
    fclose(quest_s);
    fclose(f_sumr);
}

void menu(char region[])
{
    int option,n;
    char r_1[]="Mondstadt";
    char r_2[]="Liyue";
    char r_3[]="Inazuma";
    char r_4[]="Sumeru";

    printf("Co trzeba zrobic:\n");
    printf("1 - Dodaj zadanie\n");
    printf("2 - Znajdz zadanie\n");
    printf("3 - Wypisz wszystkie zadania z regionu\n");
    printf("4 - Wyjdz z regionu\n\n");

    scanf("%d",&option);
    switch(option)
    {
    case 1:
        printf("Ile bedzie rekordow do dodania?\n");
        scanf("%d",&n);
        if(strcmp(region,r_1)==0)
            mond_w(n);
        else if(strcmp(region,r_2)==0)
            liyu_w(n);
        else if(strcmp(region,r_3)==0)
            inaz_w(n);
        else if(strcmp(region,r_4)==0)
            sumr_w(n);
        break;
    case 2:
        if(strcmp(region,r_1)==0)
            mond_s();
        else if(strcmp(region,r_2)==0)
            liyu_s();
        else if(strcmp(region,r_3)==0)
            inaz_s();
        else if(strcmp(region,r_4)==0)
            sumr_s();
        break;
    case 3:
        if(strcmp(region,r_1)==0)
            mond_ww();
        else if(strcmp(region,r_2)==0)
            liyu_ww();
        else if(strcmp(region,r_3)==0)
            inaz_ww();
        else if(strcmp(region,r_4)==0)
            sumr_ww();
        break;
    case 4:
        break;
    default:
        printf("Zle wybrano\n");
        exit(0);
    }
}

int main()
{
    int option;
    char r_1[]="Mondstadt";
    char r_2[]="Liyue";
    char r_3[]="Inazuma";
    char r_4[]="Sumeru";
    char reg[10];

    do
    {
        printf("\nPodaj region (Mondstadt, Liyue, Inazuma, Sumeru):\n");
        scanf("%s",reg);

        if(strcmp(reg,r_1)==0)
        {
            printf("\n");
            menu(reg);
        }
        else if(strcmp(reg,r_2)==0)
        {
            printf("\n");
            menu(reg);
        }
        else if(strcmp(reg,r_3)==0)
        {
            printf("\n");
            menu(reg);
        }
        else if(strcmp(reg,r_4)==0)
        {
            printf("\n");
            menu(reg);
        }
        else
        {
            printf("Zle podany region\n\n");
        }

        printf("Cos jeszcze bedzie do zrobienia(1/2)?\n");
        scanf("%d",&option);

    }while(option==1);

    return 0;
}
