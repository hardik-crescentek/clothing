<?php

error_reporting(0);

$collection = json_decode(file_get_contents('pc-api-doc.json'));

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?php echo $collection->info->name; ?></title>

    <link href="{{ url('/') }}/public/doc/css.css" rel="stylesheet" type="text/css">
    <link href="{{ url('/') }}/public/doc/prism.css" rel="stylesheet" />
</head>

<body>
    <div class="layout">
        <div class="modal dark-background" id="snippetModal" tabindex="-1" role="dialog" aria-labelledby="documentation-response-modal">
            <div class="modal-dialog" role="document">
                <div class="modal-header">
                    <div class="title"> </div>
                    <button type="button" class="close btn-circle" data-dismiss="modal" aria-label="Close">
                        <div>
                            <span aria-hidden="true">x</span>
                        </div>
                    </button>
                </div>
                <div class="modal-content">
                    <div class="modal-body">
                        <pre class=" language-php"><code class="body-block  language-php"></code></pre>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid no-gutter">
            <div class="row content-container">
                <div class="col-xs-12 info no-gutter">
                    <div id="doc-body" class="">
                        <div class="row row-no-padding row-eq-height" id="intro">
                            <div class="col-md-6 col-xs-12 section">
                                <div class="api-information">
                                    <div class="collection-name">
                                        <p><?php echo $collection->info->name; ?></p>
                                    </div>
                                    <div class="collection-description">
                                        <?php echo nl2br($collection->info->description); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-xs-12 examples">
                                <div class="sample-response">
                                    <div class="heading">
                                        <span>Example Request</span>
                                    </div>

                                    <div class="responses-index">
                                        <div class="response-status is-default">
                                            <span>Sample PHP Code</span>
                                        </div>
                                    </div>
                                    <div class="responses code-snippet">
                                        <div class="formatted-responses is-default" data-lang="php">
                                            <pre class="is-snippet-wrapper language-php">
                                                <code class="language-php">
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL =&gt; "&lcub;&lcub;base_url&rcub;&rcub;/endpoint&rcub;&rcub;",
  CURLOPT_RETURNTRANSFER =&gt; true,
  CURLOPT_ENCODING =&gt; "",
  CURLOPT_MAXREDIRS =&gt; 10,
  CURLOPT_TIMEOUT =&gt; 0,
  CURLOPT_FOLLOWLOCATION =&gt; true,
  CURLOPT_HTTP_VERSION =&gt; CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST =&gt; "GET",
  CURLOPT_HTTPHEADER =&gt; array(
    "Authorization: Bearer &lcub;&lcub;login-token-here}}"
  ),
));

$response = curl_exec($curl);

curl_close($curl);
echo $response;
</code>
                                    </pre>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <?php

                        $nev = array();

                        foreach ($collection->item as $item) :
                            $uniqid = md5(uniqid(rand(), true));
                            $nev[] = array(
                                'id' => $uniqid,
                                'method' => $item->request->method,
                                'name' => $item->name
                            );							
                        ?>
                            <div class="row row-no-padding row-eq-height" id="<?php echo $uniqid ?>">
                                <div class="col-md-6 col-xs-12 section">
                                    <div class="api-information">
                                        <div class="heading">
                                            <div class="name">
                                                <span class="<?php echo $item->request->method; ?> method" title="<?php echo $item->request->method; ?>"><?php echo $item->request->method; ?></span>
                                                <?php echo $item->name; ?>
                                            </div>
                                        </div>
                                        <div class="url"><?php echo preg_replace("#^(?=.*[0-9])(?=.*[a-z])([a-z0-9]+)#i", '&lcub;&lcub;id&rcub;&rcub;', $item->request->url->raw); ?></div>
                                        <div class="description request-description">
                                            <p><?php echo $item->request->description; ?></p>
                                        </div>
                                        <div class="headers">
                                            <div class="heading">HEADERS</div>
											<?php if(isset($item->request->auth)) : ?>
											<hr>
												<div class="param row">
                                                    <div class="name col-md-3 col-xs-12">Authorization</div>
                                                    <div class="value col-md-9 col-xs-12"><?php echo 'Bearer {{login-token-here}}'; ?></div>
                                                </div>
											<?php endif; ?>
                                            <hr>
                                            <?php foreach ($item->request->header as $header) : ?>
                                                <div class="param row">
                                                    <div class="name col-md-3 col-xs-12"><?php echo $header->key; ?></div>
                                                    <div class="value col-md-9 col-xs-12"><?php echo $header->value; ?></div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                        <?php if (isset($item->request->url->query)) : ?>
                                            <div class="query-params">
                                                <div class="heading">Query PARAMS</div>
                                                <hr>
                                                <?php foreach ($item->request->url->query as $query) : ?>
                                                    <div class="param row">
                                                        <div class="name col-md-3 col-xs-12"><?php echo $query->key ?></div>
                                                        <div class="value col-md-9 col-xs-12"><?php echo $query->value ?></div>
                                                        <div class="description col-md-9 col-xs-12">
                                                            <p><?php echo nl2br($query->description); ?></p>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>
										<?php if (isset($item->request->body)) : ?>
                                            <div class="query-params">
                                                <div class="heading">PARAMS</div>
                                                <hr>
                                                <?php foreach ($item->request->body->{$item->request->body->mode} as $formdata) : ?>
                                                    <div class="param row">
                                                        <div class="name col-md-3 col-xs-12"><?php echo $formdata->key ?></div>
                                                        <div class="value col-md-9 col-xs-12"><?php echo $formdata->value ?></div>
                                                        <div class="description col-md-9 col-xs-12">
                                                            <p><?php echo nl2br($formdata->description); ?></p>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <br><br>
                                </div>
                                <div class="col-md-6 col-xs-12 examples">

                                    <?php
                                    if (isset($item->response)) :
                                        foreach ($item->response as $response) :
                                    ?>

                                            <div class="sample-response">
                                                <div class="heading">
                                                    <span>[Response] <?php echo $response->name ?></span>
                                                </div>
                                                <div class="responses-index">
                                                    <div class="response-status is-default">
                                                        <span><?php echo $response->code ?> - <?php echo $response->status ?></span>
                                                    </div>
                                                </div>
                                                <div class="responses code-snippet">
                                                    <div class="formatted-responses is-default">
                                                        <pre class="is-example language-json" data-lang="jacasc"><code class="language-json"><?php echo trim($response->body) ?></code></pre>
                                                    </div>
                                                </div>
                                            </div>
                                    <?php
                                        endforeach;
                                    endif;
                                    ?>
                                </div>
                            </div>
                        <?php
                        endforeach;
                        ?>


                    </div>
                </div>
                <div class="no-gutter phantom-sidebar"></div>
                <div class="no-gutter sidebar" id="nav-sidebar">
                    <div class="collection-heading">
                        <p class="heading"><?php echo $collection->info->name; ?></p>
                    </div>
                    <ul class="nav navbar-nav" id="navbar-example">
                        <li class="request intro">
                            <a class="nav-link dropdown-item" href="#intro">
                                <div class="request-name">Introduction</div>
                            </a>
                        </li>
                        <li class="toc">
                            <ul></ul>
                        </li>
                        <?php foreach ($nev as $n) : ?>
                            <li class="request">
                                <div class="<?php echo $n['method']; ?> method" title="<?php echo $n['method']; ?>">
                                    <span><?php echo $n['method']; ?></span>
                                </div>
                                <div class="request-name" title="<?php echo $n['name']; ?>">
                                    <a class="nav-link dropdown-item" href="#<?php echo $n['id']; ?>">
                                        <span><?php echo $n['name']; ?></span>
                                    </a>
                                </div>
                            </li>
                        <?php
                        endforeach;
                        ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ url('/') }}/public/doc/jquery.min.js?v=3.3.1"></script>
    <script src="{{ url('/') }}/public/doc/popper.min.js?v=1.14.6"></script>
    <script src="{{ url('/') }}/public/doc/bootstrap.min.js?v=4.2.1"></script>
    <script src="{{ url('/') }}/public/doc/prism.js"></script>
    <script src="{{ url('/') }}/public/doc/main.js"></script>

</body>

</html>