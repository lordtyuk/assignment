# Useful information

## Summary

Simple application (Symfony REST backend + Angular FE) for providing filterable interface for users looking for servers

- base file stored in backend/files
- FE really simple - Material datatable with filter row
- For testing, it was planned to use test DB (still WIP)
- Hours involved: ~12

## Improvements

- USD price calculated while processing XLS for comparable prices
- storage and RAM parsing
- API endpoint for gathering possible Location / RAM / Storage etc.
- FE site filtering and sorting (WIP)

## Missing parts & known issues

- API filtering works only for location / model name - others are WIP
- Backend testing almost fully missing (preparation was done)
- Crontab not exists for XLS processing
- API XLS processing command can consume only the local file
- FE table has flaws - filters / pagination not binded

# Install & Run

## Usage

```bash
$ cd docker
```

Run environment
```bash
$ docker-compose up
```
or run in background
```bash
$ docker-compose up -d
```

To down environment
```bash
$ docker-compose down
```

## Access

Symfony API: http://localhost:8080

Angular FE: http://localhost:81
