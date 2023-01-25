# Technical challenge Stayhere

# Answers

## 1.

The past 3 days, i've been trying to improve the run time of the provided source code and i come up with the suggestions below:

-   I suggest to give meaningful variable names instead of one or two character variable name and avoid hacky ways to declare variables like `${'ls'}`.

-   I suggest to use camelCase when declaring variables and function since it's a common practice in `PHP/Laravel` ecosystem.

-   I suggest to avoid using the `for` loop unless if we really need the use the incremental value (like pagination), inside i used `foreach` because it reduce the number of declared variable and it's more readable than the normal for loop.
-   I suggest `Illuminate\Support\Facades\Http` Facade to make http requests instead of plain old curl and php functions. the primary reason why I choose to do so is: The Http facade gives a fluent way to make an HTTP request in an OOP style since where are focusing on writing clean, modern and readable code.

-   in the code below, i took advantage of newest `php8.0` feature which is **named arguments** to avoid setting the value `SimpleXMLElement` to `$class_name` parameter since it is the default value and `options` parameter is a must.

    -   old code

        ```php
        simplexml_load_string($d, 'SimpleXMLElement', LIBXML_NOCDATA)
        ```

    -   new code

        ```php
        simplexml_load_string(data: $response->body(), options: LIBXML_NOCDATA);
        ```

-   i suggest `str_contains` function instead of `substr_count` or `strstr` since it's more readable, straightforward and easy for the brain to process the result.

-   I suggest to create variable name for duplicated function return, for example:

    ```php
    // bad practice
    (string)$c->item[$I]->children("content", true)
    (string)$c->item[$I]->children("content", true)
    (string)$c->item[$I]->children("content", true)

    // good practice
    $itemContent = (string)$c->item[$I]->children("content", true);
    ```

-   I suggest to avoid explicit variable checking if it null or empty etc..., php is smart enough to detect if a variable is set on or not by just giving the value of the variable to an `if` statement, for example:

    ```php
    // bad practice
    if($j->articles[$II]->urlToImage=="" || empty($j->articles[$II]->urlToImage) || strlen($j->articles[$II]->urlToImage)==0){}

    // good practice
    if($j->articles[$II]->urlToImage){}

    ```

-   Before I start writing my implementation to solve a problem, I try to look for a build-in function to solve it because build-in functions are well-tested and written by experienced and smarter people than me. for example:

    ```php
    // good practice
    private function duplicate($t1, $t2){
        foreach($t1 as $k1=>$v1){
            $duplicate=0;
            foreach($t2 as $v2){if($v2==$v1){$duplicate=1;}}
        }
        return $duplicate;
    }

    // better practice
    array_unique([...$t1, ...$t2]);
    ```

-   I suggest to avoid instantiating a class without dynamic paramters multiple times instead instantiate it ones, for example:

    ```php
    // bad practice
    while(){
        $doc = new \DomDocument();
        @$doc->loadHTMLFile($l);
    }

    // good practice
    $doc = new \DomDocument();
    while(){
        @$doc->loadHTMLFile($l);
    }

    // bad practice
    if(strstr($l, "commitstrip.com"))
    {
        $doc = new \DomDocument();
        @$doc->loadHTMLFile($l);
    }
    else
    {
        $doc = new \DomDocument();
        @$doc->loadHTMLFile($l);
    }

    // good practice
    $doc = new \DomDocument();
    if(strstr($l, "commitstrip.com"))
    {
        @$doc->loadHTMLFile($l);

    }
    else
    {
        @$doc->loadHTMLFile($l);
    }
    ```

-   I suggest to avoid using `else` block if the `if` has a return keyword in inside, for example:

    ```php
    // bad practice
    if(strstr($l, "commitstrip.com"))
    {
        $doc = new \DomDocument();
        @$doc->loadHTMLFile($l);
        $xpath = new \DomXpath($doc);
        $xq = $xpath->query('//img[contains(@class,"size-full")]/@src');
        $src=$xq[0]->value;

        return $src;
    }
    else
    {
        $doc = new \DomDocument();
        @$doc->loadHTMLFile($l);
        $xpath = new \DomXpath($doc);
        $xq = $xpath->query('//img/@src');
        $src=$xq[0]->value;

        return $src;
    }


    // good practice
    $doc = new \DomDocument();
    @$doc->loadHTMLFile($l);
    if(strstr($l, "commitstrip.com"))
    {
        $xpath = new \DomXpath($doc);
        $xq = $xpath->query('//img[contains(@class,"size-full")]/@src');
        $src=$xq[0]->value;

        return $src;
    }

    $xpath = new \DomXpath($doc);
    $xq = $xpath->query('//img/@src');
    $src=$xq[0]->value;

    return $src;
    ```

<br>

-   **Finally**, i think i coverted all the suggestions i used to improve the runtime of the script, i hope i didn't forget anything. in case i did, you can ask me about it, i will be more than happy to give an anwser to your question.

<br>

## 2.

Since you didn't give much details about this question, i assume that to make this script scale and support thousands of image sources is simply implementing **Pagination**.
