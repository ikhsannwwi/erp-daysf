<div class="row mb-3" id="filter_section" style="display: none;">
    <div class="col-md-12">
        <form id="filter_form">
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-6 pt-3">
                            <div class="form-group fv-row">
                                <label class="required form-label">Status</label>
                                <select class="form-select btn-sm form-select-solid" data-hide-search="true"
                                    id="filterstatus">
                                    <option value="">Semua</option>
                                    <option value="Aktif">Aktif</option>
                                    <option value="Tidak Aktif">Tidak Aktif</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row my-3">
                    <div class="col-md-12">
                        <div class="d-flex gap-1 float-end">
                            <button type="reset" id="reset-btn" class="btn btn-danger text-white">Reset</button>
                            <button id="filter_submit" class="btn btn-primary">Filter</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <!--end::Card toolbar-->
</div>
