<nav class="navbar navbar-expand-sm bg-dark navbar-dark">
                    <a href="#" class="navbar-brand">Nanopubs Map</a>

                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="collapsibleNavbar">
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a href="/~ia48/NanoTopicMapping/index.php" class="nav-link">Home</a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Search
                              </a>
                              <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="/~ia48/NanoTopicMapping/search_nanopubs_keyword.php">Keyword Search</a>
                                <a class="dropdown-item" href="search_nanopubs_iri.php">IRI Search</a>
                              </div>
                            </li>
                            <li class="nav-item dropdown">
                              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Concept Grouping
                              </a>
                              <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="/~ia48/NanoTopicMapping/clusters/topic_iri.php">By IRI</a>
                                <a class="dropdown-item" href="/~ia48/NanoTopicMapping/clusters/labels.php">By rdfsLabels</a>
                                <a class="dropdown-item" href="/~ia48/NanoTopicMapping/clusters/ims_merge.php">By IMS</a>
                                <!-- <a class="dropdown-item" href="/~ia48/NanoTopicMapping/clusters/label_ims.php">Label + IMS</a> -->
                                <!-- <a class="dropdown-item" href="/~ia48/NanoTopicMapping/clusters/sampling.php">Sampling</a> -->
                              </div>
                            </li>
                            <li class="nav-item dropdown">
                              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Boxplots
                              </a>
                              <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <!-- <a class="dropdown-item" href="/~ia48/NanoTopicMapping/boxplots/topic_iri_label.php">Topic IRI & rdfsLabel</a> -->
                                <!-- <a class="dropdown-item" href="/~ia48/NanoTopicMapping/boxplots/ims_ims_merge.php">IMS & (IMS + Merge)</a> -->
                                <a class="dropdown-item" href="/~ia48/NanoTopicMapping/boxplots/topic_label_ims_merge.php">IRI, Label & IMS</a>
                                <!-- <a class="dropdown-item" href="/~ia48/NanoTopicMapping/boxplots/all_in_one.php">All in one</a> -->
                              </div>
                            </li>
                        </ul>
                    </div>
                </nav>