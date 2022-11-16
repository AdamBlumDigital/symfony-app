# App

A Symfony Project.

The goal of this project is do avoid strong coupling between
the framework (Symfony) and the domain. 


Basic cURL command for POSTing JSON:

```
curl -i \
-H "Content-type: application/json" \
-X POST \
-d '{"title": "Example"}' \
https://localhost:8000/route
```
