{def $keep = array( "<b>", "<i>", "<em>", "<strong>", "<h2>", "<h3>", "<h4>", "<h5>", "<h6>", "<br/>", "<p>", "<ul>", "<ol>", "<li>", "<a>" )}
<?xml version="1.0" encoding="utf-8" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <title>{$datamap.title.content|wash}</title>
    </head>
    <body>
        <h1>{$datamap.title.content|wash}</h1>
        {if $datamap.intro.has_content}
        <div class="attribute-intro">
            {$datamap.intro.content.output.output_text|keeptags( $keep )}
        </div>
        {/if}
        <div class="attribute-body">
             {$datamap.body.content.output.output_text|keeptags( $keep )}
        </div>
    </body>
</html>
