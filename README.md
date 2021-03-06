# botsArena
Source code of [bot's Arena](https://botsarena.tinad.fr/)

It's a website for programming AI challenges. Everyone can read a game's specifications. Program a bot for play to it. Host the bot and register on bot's Arena.

![image](https://raw.githubusercontent.com/gnieark/botsArena/master/html/principe.gif)

# Still in dev
- The stable website (Using github API, Vhost is synchronized to branch master) is [https://botsarena.tinad.fr/](https://botsarena.tinad.fr/)

# Contribute
You are welcome, Make pull requests on branch dev. Im available on twitter [@gnieark](https://twitter.com/gnieark) in order to talk.

# install it
I'd better like you to help me to improve [bot's Arena](https://botsarena.tinad.fr/), and you to make your own bots to play challenges. It would be more funny having lot of bots in one arena rather than lot of arenas.

See dockerFile and associated scripts to understand what is needed.

To test an image of bots arena with docker:

    make
    docker build .
    docker run --name hey -p 127.0.0.1:8080:80 DockerImageId

For nginx Rewrite rules in server directive are:

    rewrite '^/([a-zA-Z]{1,})/doc-([a-z]{2})$' /index.php?doc=$1&lang=$2 last;
    rewrite '^/p/([a-zA-Z]{1,})/(.*)-([a-z]{2})$' /index.php?page=$1&params=$2&lang=$3 last;        
    rewrite '^/p/([a-zA-Z]{1,})/(.*)$' /index.php?page=$1&params=$2 last;
    rewrite '^/p/(.*)-([a-z]{2})$' /index.php?page=$1&lang=$2 last;
    rewrite '^/p/(.*)$' /index.php?page=$1 last;
    rewrite '^/([a-zA-Z]{1,})/scores$' /index.php?scores=$1 last;
    rewrite '^/([a-zA-Z]{1,})-([a-z]{2})$' /index.php?arena=$1&lang=$2 last;
    rewrite '^/([a-zA-Z]{1,})/doc$' /index.php?doc=$1 last;
    rewrite '^/([a-zA-Z]{1,})$' /index.php?arena=$1 last;
        
# License
Bot's Arena , Website for Artificials intelligences duels.

Copyright (C) 2015-2016 [Gnieark](https://blog-du-grouik.tinad.fr/)

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
