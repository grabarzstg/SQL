/*
# Projekt 1 
# Program operujący na bazie PostgreSQL
# (c) Grabowski Marek 30.03.2014
*/


#include <stdlib.h>
#include <libpq-fe.h> 
#include <string.h>
#include "colour.h"  
#include "passwords.h"

#define DUZO 100




void doSQL(PGconn *conn, char *command)
{
  PGresult *result;

  printf("%s\n", command);

  result = PQexec(conn, command); 
  printf("STATUS        : %s\n", PQresStatus(PQresultStatus(result)));
  printf("#rows affected: %s\n", PQcmdTuples(result));
  printf("result message: %s\n", PQresultErrorMessage(result));

  switch(PQresultStatus(result)) {
  case PGRES_TUPLES_OK:
    {
      int n = 0, m = 0;
      int nrows   = PQntuples(result);
      int nfields = PQnfields(result);
      printf("number of rows returned   = %d\n", nrows);
      printf("number of fields returned = %d\n", nfields);
      for(m = 0; m < nrows; m++) {
	for(n = 0; n < nfields; n++)
	  printf(" %s = %s", PQfname(result, n),PQgetvalue(result,m,n));
	printf("\n");
      }
    }
  }
  PQclear(result);
}
/*      DROP     */
void drop(PGconn *conn, char *name)
{
  if(PQstatus(conn) == CONNECTION_OK) {
	printfc("DONE: \n", GREEN);
	char drop[DUZO]="DROP TABLE ";
	strcat(drop, name);
    doSQL(conn, drop);
 //   doSQL(conn, "CREATE TABLE number(value INTEGER PRIMARY KEY, name VARCHAR)");

  }
  else
	printfc("Connection failed: ",RED);
    printf("%s\n", PQerrorMessage(conn));
}
/*     CREATE   */
void create(PGconn *conn, char *name)
{
  if(PQstatus(conn) == CONNECTION_OK) {

    printfc("DONE: \n", GREEN);
	char create[DUZO]="CREATE TABLE ";
	char *param="(id INTEGER PRIMARY KEY, username VARCHAR, name VARCHAR, surname VARCHAR)";
	strcat(create, name);
	strcat(create, param);
    doSQL(conn, create);
  }
  else
  printfc("Connection failed: ",RED);
    printf("%s\n", PQerrorMessage(conn));
}

/*     ALTER - ADD   */
void alter_add(PGconn *conn, char *name, char *kolumna, char* typ)
{
  if(PQstatus(conn) == CONNECTION_OK) {

    printfc("DONE: \n", GREEN);
	char create[DUZO]="ALTER TABLE ";
	char *add=" ADD COLUMN ";
	
	strcat(create, name);
	strcat(create, add);
	strcat(create, kolumna);
	strcat(create, typ);
    doSQL(conn, create);
  }
  else
  printfc("Connection failed: ",RED);
    printf("%s\n", PQerrorMessage(conn));
}
/*     ALTER - DROP   */
void alter_drop(PGconn *conn, char *name, char *kolumna)
{
  if(PQstatus(conn) == CONNECTION_OK) {

    printfc("DONE: \n", GREEN);
	char create[DUZO]="ALTER TABLE ";
	char *add=" DROP COLUMN ";
	char *param=" RESTRICT";
	strcat(create, name);
	strcat(create, add);
	strcat(create, kolumna);
	strcat(create, param);
    doSQL(conn, create);
  }
  else
  printfc("Connection failed: ",RED);
    printf("%s\n", PQerrorMessage(conn));
}

/* INSERT */

void insert(PGconn *conn, char *where, char *id, char *one, char* two, char* three)
{
  if(PQstatus(conn) == CONNECTION_OK) {

    printfc("DONE: \n", GREEN);
	char create[DUZO]="INSERT INTO ";
	char *val=" values(";
	strcat(create, where);
	strcat(create, val);
	strcat(create, id);
	strcat(create, ", '");
	strcat(create, one);
	strcat(create, "', '");
	strcat(create, two);
	strcat(create, "', '");
	strcat(create, three);	
	strcat(create, "')");
    doSQL(conn, create);
  }
  else
  printfc("Connection failed: ",RED);
    printf("%s\n", PQerrorMessage(conn));
}

/*     UPDATE   */
void update(PGconn *conn, char *name, char *nickname, char* id)
{
  if(PQstatus(conn) == CONNECTION_OK) {

    printfc("DONE: \n", GREEN);
	char create[DUZO]="UPDATE ";
	char *add=" ADD COLUMN ";
	
	strcat(create, name);
	strcat(create, " SET username=' ");
	strcat(create, nickname);
	strcat(create, "' WHERE id = ");
	strcat(create, id);
    doSQL(conn, create);
  }
  else
  printfc("Connection failed: ",RED);
    printf("%s\n", PQerrorMessage(conn));
}

/*     DELETE ID   */
void delete_id(PGconn *conn, char *name, char *id)
{
  if(PQstatus(conn) == CONNECTION_OK) {

    printfc("DONE: \n", GREEN);
	char create[DUZO]="DELETE FROM ";	
	strcat(create, name);
	strcat(create, " WHERE id= ");
	strcat(create, id);
    doSQL(conn, create);
  }
  else
  printfc("Connection failed: ",RED);
    printf("%s\n", PQerrorMessage(conn));
}

/*     SELECT ALL   */
void select_all(PGconn *conn, char *name)
{
  if(PQstatus(conn) == CONNECTION_OK) {

    printfc("DONE: \n", GREEN);
	char create[DUZO]="SELECT * FROM ";	
	strcat(create, name);
    doSQL(conn, create);
  }
  else
  printfc("Connection failed: ",RED);
    printf("%s\n", PQerrorMessage(conn));
}

/*    TRIGGER    */
void trigger_add(PGconn *conn){
  if(PQstatus(conn) == CONNECTION_OK) {
    printfc("DONE: \n", GREEN);
    doSQL(conn, "CREATE OR REPLACE FUNCTION dodatnie() RETURNS TRIGGER AS $$ BEGIN IF NEW.id < 1 THEN RAISE EXCEPTION '% id must be greater than zero', NEW.id; END IF; RETURN NEW; END; $$ LANGUAGE 'plpgsql';");
    doSQL(conn, "CREATE TRIGGER number_insert BEFORE INSERT ON number FOR EACH ROW EXECUTE PROCEDURE dodatnie();");

  }
  else
  printfc("Connection failed: ",RED);
    printf("%s\n", PQerrorMessage(conn));
}

/* HTML  */
void html(PGconn *conn, PGresult *result)
{
    if(PQresultStatus(result)==PGRES_TUPLES_OK) 
    { 
      PQprintOpt pqp;
      pqp.header = 1;
      pqp.align = 1;
      pqp.html3 = 1;
      pqp.expanded = 0;
      pqp.pager = 0;
      pqp.fieldSep = "";
      pqp.tableOpt = "align=center";
      pqp.caption = "Bingham Customer List";
      pqp.fieldName = NULL;
	  FILE *fp;
	  if ((fp=fopen("index.html", "w"))==NULL) {
     printf ("Nie mogę otworzyć pliku index.html do zapisu!\n");
     exit(1);
     }
	  fprintf (fp, "%s", "<HTML> <HEAD> </HEAD> <BODY>\n"); /* zapisz nasz łańcuch w pliku */
      PQprint(fp, result, &pqp);
	  fprintf (fp, "%s", "</BODY> </HTML>\n");
	  fclose (fp);
    }
}

// MAIN
int main()
{
system("clear");
  PGresult *result;
  PGconn   *conn;
conn = PQconnectdb(passwords);
result = PQexec(conn, "SELECT * FROM number");
drop(conn, "number");
create(conn, "number");
trigger_add(conn);
//alter_add(conn, "number", "skype ", "VARCHAR");
//alter_drop(conn, "number", "skype ");
insert(conn,"number", "1", "DAKA", "Rafal", "Daca");
//update(conn,"number", "DACA", "1");
//select_all(conn,"number");
//delete_id(conn, "number", "1");
insert(conn,"number", "0", "DAKA", "Rafal", "Daca");
html(conn, result);
    


	select_all(conn,"number");
  PQfinish(conn);
  return EXIT_SUCCESS;
}

