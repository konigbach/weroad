![a](https://theme.zdassets.com/theme_assets/9115960/ef5800cc529889d180b05b57e40dd50e5c7adb73.png)

### TOOLS 
- Laravel 10.
- Laravel Pint (formatter).
- Phpstan/Larastan (static analyzer).
- PhpUnit (testing).
- Barry Laravel IdeHelper (it helps to have the models up to date with the fields and not using magic attributes and needed for Phpstan).

### COMMENTS AND EXPLANATIONS
- Ideally, 'users' table should be used for _users_ and another table for _admins_
- Obviously, this is an example of a CRUD, but approaches like Hexagonal Architecture pays off in the long run. 
For example, the use of DTOs make more sense when you have a Domain Driven Design and everything related to a domain in the same place.
- In the request, I like to work with type hints (a 'problem' with $request->thingy is that you don't know what's 'thingy').
I have used RequestServiceProvider for the sake of an example in how to extend the Framework in a clean way.
- In the List Tours endpoint, I have used ```Criteria pattern``` to filter the results, just for the sake of using a design pattern.

### DOCS
- Inside ```docs``` folder you can find the ```changelog``` and the openapi.yaml
initial doc. Inside each endpoint folder you have the specifications (with ChatGPT or similar, you can 
generate based on the form request and response the document really fast). 
