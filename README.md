# Magento 2 Timoffmax_Topbar

Top Information Bar allows to draw the attention of visitors and engage them to introduce new offers, discount, deals, cookie restriction policy information
etc.
You can set up dedicated top bars for different store views and also set up several ones.

## Requirements
- PHP >= 7.1
- Magento >= 2.2

## Installation
- Create a folder `mkdir -p app/code/Timoffmax/Topbar`
- Copy module's content to `app/code/Timoffmax/Topbar`
- `bin/magento module:enable Timoffmax_Topbar`
- `bin/magento setup:uprgade`

## Configuration
1. Enable the module in **Stores -> Settings -> Configuration -> CTI Digital -> Despatch Countdown Timer**.
2. Add your topbar in **Marketing -> Notification -> Top Information Bar**.
3. Flush cache.

## To Improve
- Invalidate cache on add/update a topbar;
- Move access to settings to separated class;
- Add composer support.
