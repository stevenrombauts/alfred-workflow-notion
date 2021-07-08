# Alfred Workflow for Notion API

An Alfred Workflow to search through your Notion workspace with the Notion API.

![Workflow example](https://github.com/stevenrombauts/alfred-workflow-notion/blob/master/preview.gif?raw=true)

## Installation

Download [Notion.alfredworkflow](https://github.com/stevenrombauts/alfred-workflow-notion/releases/download/v1.0.0/Notion.alfredworkflow) and open to install in Alfred.

## Configuration 

1. Create a new [Internal Integration]( https://www.notion.com/my-integrations). 
1. Copy the "Internal Integration Token" into the `NOTION_API_KEY` environment variable in this workflow.
1. Each page must be shared with the integration before they can be accessed using the API. Press the "Share" button on top of each page and add the integration. Note that you don't have to share subpages, the root pages suffice.

The [Notion docs](https://developers.notion.com/docs/getting-started) explain these steps very well. 

Now you can search through your Notion workspace by hitting your hotkey, typing `ns` followed by the search string.

If you want to open links in the desktop app instead of the browser, change the `useDesktop` environment variable to "true".

## Questions?

Feel free to open an issue or contact me on [Twitter](https://twitter.com/stevenrombauts). 

## Disclaimer

I give no guarantees that this workflow will work for you and work correctly. Works on my machine so far! 😇
