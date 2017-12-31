<!DOCTYPE html>
    <head>
        <title>Ivan Neradovic MD5 Cracker</title>
    </head>
    <body>
        <h1>MD5 cracker</h1>
        <p>This application takes an MD5 hash of a four digits string and attempts to hash all combinations to determine the original PIN.</p>

        <pre>
Debug Output:
<?php
                $goodtext = "Not found";
                // If there is no parameter, this code is all skipped
                if ( isset($_GET['md5']) ) {
                    $time_pre = microtime(true);
                    $md5 = $_GET['md5'];

                    // This is our alphabet
                    $show = 15;

                    // Outer loop go go through the alphabet for the
                    // first position in our "possible" pre-hash text
                    for($i=0; $i<100; $i++) {
                        $ch1 = $i;   // The first of two characters

                        if ($ch1 < 10)
                        {
                            $ch1 = "0".$ch1;
                        }

                        // Our inner loop Not the use of new variables
                        // $j and $ch2 
                        for($j=0; $j<100; $j++) {
                            $ch2 = $j;  // Our second character

                            // Concatenate the two characters together to 
                            // form the "possible" pre-hash text
                            if ($ch2 < 10)
                            {
                                $ch2 = "0".$ch2;
                            }

                            $try = $ch1.$ch2;

                            // Run the hash and then check to see if we match
                            $check = hash('md5', $try);
                            if ( $check == $md5 ) {
                                $goodtext = $try;
                                break;   // Exit the inner loop
                            }

                            // Debug output until $show hits 0
                            if ( $show > 0 ) {
                                print "$check $try\n";
                                $show = $show - 1;
                            }
                        }
                    }

                    // Compute ellapsed time
                    $time_post = microtime(true);
                    print "Ellapsed time: ";
                    print $time_post-$time_pre;
                    print "\n";
                }
            ?>
        </pre>

        <!-- Use the very short syntax and call htmlentities() -->
        <p>PIN: <?= htmlentities($goodtext); ?></p>

        <form>
            <input type="text" name="md5" size="60" />
            <input type="submit" value="Crack MD5"/>
        </form>

        <ul>
            <li><a href="index.php">Reset</a></li>
            <li><a href="md5.php">MD5 Encoder</a></li>
            <li><a href="makecode.php">MD5 Code Maker</a></li>
            <li>
                <a href="https://github.com/csev/wa4e/tree/master/code/crack" target="_blank">
                    Source code for this application
                </a>
            </li>
        </ul>

    </body>
</html>

