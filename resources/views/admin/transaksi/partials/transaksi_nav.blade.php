<nav class="navbar navbar-expand-lg navbar-light">
    <div class="navbar-nav">
    </div>
    <button class="navbar-toggler" type="button" data-toggle="collapse"
        data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false"
        aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNavDropdown">
        <div class="navbar-collapse-header">
            <div class="row">
                <div class="col-7 collapse-close">
                    <button type="button" class="navbar-toggler" data-toggle="collapse"
                        data-target="#navbarNavDropdown" aria-controls="navbar-default"
                        aria-expanded="false" aria-label="Toggle navigation">
                        <span></span>
                        <span></span>
                    </button>
                </div>
            </div>
        </div>
        <ul class="navbar-nav d-flex align-items-center">
            <li class="nav-item {{ $active=="pending" ? "btn btn-primary":"" }}">
                <a class="nav-link {{ $active=="pending" ? "text-white" : "" }}" id="nav-link" href="/transaksi/keluar/pending">Pending</a>
            </li>
            <li class="nav-item {{ $active=="approve" ? "btn btn-primary":"" }}">
                <a class="nav-link {{ $active=="approve" ? "text-white" : "" }}" id="nav-link" href="/transaksi/keluar/approve">Approve</a>
            </li>
            <li class="nav-item {{ $active=="ongoing" ? "btn btn-primary":"" }}">
                <a class="nav-link {{ $active=="ongoing" ? "text-white" : "" }}" id="nav-link" href="/transaksi/keluar/ongoing">Ongoing</a>
            </li>
            <li class="nav-item {{ $active=="completed" ? "btn btn-primary" : "" }}">
                <a class="nav-link {{ $active=="completed" ? "text-white" : "" }}" id="nav-link" href="/transaksi/keluar/completed">Completed</a>
            </li>
            <li class="nav-item {{ $active=="cancel" ? "btn btn-primary" : "" }}">
                <a class="nav-link {{ $active=="cancel" ? "text-white" : "" }}" id="nav-link" href="/transaksi/keluar/cancel">Cancel</a>
            </li>

        </ul>
    </div>
</nav>