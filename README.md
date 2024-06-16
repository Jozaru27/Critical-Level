# <img src="https://github.com/Jozaru27/Critical-Level/blob/main/media/star-spinning.gif" width="30" height="30"/> Critical-Level <img src="https://github.com/Jozaru27/Critical-Level/blob/main/media/star-spinning.gif" width="30" height="30"/> ![version 0.0.1](https://img.shields.io/badge/version-0.0.1-brightgreen)
<!-- BADGE TAKEN FROM: https://tekiter.github.io/shields-craft/ -->
*Home repository for the ***Critical Level*** Page - a Web Project* <br><br>

![](https://github.com/Jozaru27/Critical-Level/blob/main/media/separator.png)

![](https://github.com/Jozaru27/Critical-Level/blob/main/media/welcome.gif)

Critical Level is a web project in which I aspire to create, through my vision, a functional page in which people can express their thoughts and rankings about games, leaving reviews so other players can watch, read and comment, essentially exchanging information about their favourite games. 

The page will work as a IMDB-like service, in which people can either check other people's opinions or analysis on videogames, while also having the oportunity to express themselves to other people, and to discuss different aspects.

> [!WARNING]
> This project is still being worked on, and so it's its documentation. Please be patient as stuff might be confusing at first!

![](https://github.com/Jozaru27/Critical-Level/blob/main/media/separator.png)

<br><br>
## Roadmap / To Do / Steps <img src="https://github.com/Jozaru27/Critical-Level/blob/main/media/mario.gif" width="42" height="42"/>

| Status 📍 | Goal 🚀 |
| :---: | --- |
| ✔️ | Create Repository to document changes and upload files  |
| ✔️ | Install XAMPP + Open ports + Set Database  |
| ✔️ | Obtain a domain (NO-IP) - Linked with XAMPP  |
| ❌ | Set SSL Certificate |
| ✔️ | Obtain a good Videogame API - RAWG |
| ✔️ | Design proper database + Create it with Workbench |
| ✔️ | Create main Design with HTML & CSS (+ libraries/frameworks) |
| ✔️ | Validate HTML & CSS |
| ✔️ | Test PHP for DB/HTML interaction & integration  |
| ✔️ | Display any DB info + data access within the webpage |
| ✔️ | Display any API info within the webpage |
| ✔️ | Fixed AJAX CORS - Access-Control-Allow-Origin + Validated Site |
| ✔️ | Add login/register system + sessions (with dependant navs)|
| ✔️ | Add roles for certain accesses |
| ✔️ | Add basic profile data retrieval |
| ✔️ | Dynamic Profiles |
| ✔️ | Add profile edition/deletion |
| ✔️ | Create proper games page + Pretty API |
| ✔️ | Add sessions and roles security checks |
| ✔️ | Main Page (dependant on certain review checks) |
| ✔️ | Events Page + Buy membership & change role page |
| ❌ | Test web with ZAP for any security breach |

* Games Page - Better Pagination and Search Bar System + Add filters
* Refactor and Reduce Code + English Commentary
* Landing Page + Prettier stats + DB stats
* Age check WHEN REGISTERING OR ACCESSING GAMES - Could use cookies
* Fix error header login
* Login Forgot Password
* Login No Account? Register
* Register Already Account? Log in
* Password Email check
* Flag for country - at profile
* Fix promise code - Main Script in landing page
* Check all links and routes
* Separate scripts and styles

* Automatic Moderation
* imgGen = https://dynamic-image.vercel.app/#eyJ0aGVtZSI6InJhbmRvbSJ9
* ZAP AND https://cors-test.codehappy.dev/
* Accesibilidad
* Check HTML and CSS validation

![](https://github.com/Jozaru27/Critical-Level/blob/main/media/separator.png)
<br><br>

## First in depth look at the objective 🎯

The goal of this project, is to be able to host a page that hosts an ample selection of videogames, on which the registered or logged in users might be able to post reviews. The reviews will include a numeric score (either 1 to 5 stars, or 0/10 - 10/10, etc).
Other users, while still being able to post their own reviews, can choose to answer to an already posted review from another user, thus, creating a comment thread, in which they can discuss more profoundly said person's opinion, or add to it, etc.
That being the basic functionality of the page im trying to achieve, i'd like to delve further within, to cover additional features or technicalities. 

There's gonna be at least 2 user types: The admins and the users. While the users will do what was mentioned beforehand, the admins will make sure that the page functions correctly, check for manual moderation in the comments section and the reviews, as well as
manually adding the game, if the use of an API isn't viable. 

Now, when it comes to monetization, I would like to go further than annoying ads and/or kickstarters. The page could hold "benefit" products (will have to investigate what could be profitable without it ruining the user's experience). Those benefits could range from either being able to post more reviews or comments a day, being able to have a special supporter badge and name color, accessing restricted parts of the page, such as forums, on-line chats, being able to post guides, or images within the reviews. If there ever is a service like a "Match Making" (yes, a Tinder for gaming buddies), that could too be a paid option. Maybe even the page could hold online events, or promote phyiscal ones which you can only attend to by being a member. This must be explored, but not exploited.

A profile system could be implemented, to check each user's reviews, yes, but to also see a profile picture, short bio, stats like reviews posted, days logged-in, account creation date, or if it's ever implemented, a badge and reward system (posted 10 times, had a review with a score of 100 or more, etc)

![](https://github.com/Jozaru27/Critical-Level/blob/main/media/separator.png)
<br><br>


<!-- Other API's: SteamWebAPI, IGDB, RAWG, Launchbox, Openretro, MobyGames, Metropolis Launcher, Screenscraper, Skraper, TheGamesDB, GameTDB, Giant Bomb--> 

![](https://github.com/Jozaru27/Critical-Level/blob/main/media/separator.png)
<br><br><br>

## Technologies used ⚙️

* **NO-IP:** Webpage hosting, DDNS, domain solving, IP forwarding, etc
* **XAMPP:** Apache testing, Database and a nexus on where to store files.
* **HTML5 & CSS, Bootstrap, JS, AOS:** To create the webpage
* **API - RAWG:** To obtain videogame data and such
* **PHP:** Process Forms, connect to DB + Login System & Sessions

<br><br>

![](https://github.com/Jozaru27/Critical-Level/blob/main/media/gary.gif)
