<script>
    import { PowerTable } from '@muonw/powertable';
    let MyPowerTable ; 
    let ptData= [{"Tarikh Daftar": 1, "name": "Fay"}, {"Tarikh Daftar": 2, "name": "Luca"}];
    let ptOptions = {
        checkboxColumn: true,
        segments: {
            'topBar': ['settings', 'search', 'pagination'],
            'pTable': ['table'],
            'bottomBar': ['dropdown', 'stats', 'pagination'],
        }
    }

    function exportJsonData(e) {

        MyPowerTable.closeMenu(e);
        let currentData = MyPowerTable.getData().data;

        // Download JSON object as a file from browser (https://stackoverflow.com/a/30800715)
        var dataStr = 'data:text/json;charset=utf-8,' + encodeURIComponent(JSON.stringify(currentData));
        var downloadAnchorNode = document.createElement('a');
        downloadAnchorNode.setAttribute('href', dataStr);
        downloadAnchorNode.setAttribute('download', 'PowerTable_current_data.json');
        document.body.appendChild(downloadAnchorNode); // required for firefox
        downloadAnchorNode.click();
        downloadAnchorNode.remove();
       
        
    }

    </script>
    
 
    
    <style global>
    @import '../../node_modules/@muonw/powertable/package/dist/power-table.css';
    </style>
    
    <main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Dashboard</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>
        <div class="row">
            <div class="col-xl-3 col-md-6">
                <div class="card bg-primary text-white mb-4">
                    <div class="card-body">Primary Card</div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="#">View Details</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card bg-warning text-white mb-4">
                    <div class="card-body">Warning Card</div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="#">View Details</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card bg-success text-white mb-4">
                    <div class="card-body">Success Card</div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="#">View Details</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card bg-danger text-white mb-4">
                    <div class="card-body">Danger Card</div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="#">View Details</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-chart-area me-1"></i>
                        Area Chart Example
                    </div>
                    <div class="card-body"><canvas id="myAreaChart" width="100%" height="40"></canvas></div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-chart-bar me-1"></i>
                        Bar Chart Example
                    </div>
                    <div class="card-body"><canvas id="myBarChart" width="100%" height="40"></canvas></div>
                </div>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-table me-1"></i>
                DataTable Example
            </div>
            <div class="card-body">
                <div class="MuonW PowerTable">
                    <PowerTable {ptData} {ptOptions} bind:this={MyPowerTable}>
                        <div slot="settings">
                            <div data-name="item" on:click={exportJsonData}>Export current data</div>
                        </div>
                    </PowerTable>
                </div>
            </div>
        </div>
    </div>
</main>