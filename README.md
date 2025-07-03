# UniOfSalford-StudentWork-AdvancedWebDevelopmentAssignment
Advanced Web Development Assignment(1&amp;2) - Library website using API's and Google Books. Uses Symfony

Assignent 1 Brief: 
Core Requirements
Your web application should allow users to register, log in/out, list, create, view, edit and delete
book reviews. The review information should include book title, author, number of pages,
summary, genre as well as the name of the reviewer and the actual review text. Non-
authenticated users should not be able to write/edit/delete reviews but should be able to view
them.
Intermediate Requirements
The application should allow multiple users to review a single book. Thus, meaning a user
should be able to add a book if it does not exist, and subsequently other people should be able
to write reviews about the book that has been created.
Advanced Requirements
The application should be well-structured, demonstrating a clear understanding of Symfony
framework/components and application design including decoupling of functionality. In
addition, extra features as users would expect from a real-world system should be implemented
– such as (but not limited to):
• Multiple role levels (e.g. user, moderator and administrator)
• Advanced search functionality (such as faceted search)
• Advanced review rating system
• Image upload (book covers, user profile pictures etc.)

Assignment 2 Brief:
API Implementation
You are required to implement an API which will allow a third party application to list, read,
create, edit and delete book reviews. This implementation should both accept and return code
in JSON format, and the API should be entirely RESTful with the appropriate use of HTTP verbs
and resource names. For additional marks you should include a logical resource structure
complete with sub-resources and also have professional authentication (OAuth/JWT).
API Consumption
You are required to consume a RESTful web service related to information about books such as
the Google Books API (https://developers.google.com/books/) or a comparable service of your
own choice with a view to extending the functionality of your Book Review Website.
Please Note: You may need to register for an account depending on the service you choose.
There are plenty of free services and you should not rely on a paid-for service. You should pick a
web service that is reasonably reliable allowing you to demonstrate your system when your
work is assessed.
You are expected to have at least two different examples of API interaction, and the content
should be integrated into your Review website. For your API consumption you must use the
Guzzle library. For a better mark you should also integrate at least one other non-trivial web
service to enhance the functionality and/or experience of your website.
