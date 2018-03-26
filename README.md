# VulnBank

This application emulates modern Web 2.0 application and has several vulnerabilities related to OWASP Top10, business logic or architecture-level issues.

Dependencies: PHP, MySQL

Vulnerabilities list:
- Business logic issues
- DOM-based Cross-Site Scripting (XSS)
- Stored Cross-Site Scripting (XSS)
- Cross-Site Request Forgery (CSRF)
- Race Condition
- XML External Entity (XXE)
- Session Hijacking
- Remote Code Execution (ImageTragick, CVE-2016-3714)

Features list:
- Different API-types: XML, REST, ordinary POST-request
- Nexmo SMS-gateway integration

## Docker deployment
You can deploy ready-to-use container from vulnbank/vulnbank:

```docker run --name vulnbank -p 80:80 -d vulnbank/vulnbank```

Or build your own with following command:

```docker build . -f Dockerfile```
