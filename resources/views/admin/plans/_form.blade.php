<div class="form-row">
    <label>Nama Paket <span class="req">*</span></label>
    <input type="text" name="name" value="{{ old('name', $plan->name) }}" required maxlength="120" class="admin-input">
</div>
<div class="form-row">
    <label>Deskripsi</label>
    <textarea name="description" maxlength="1000" class="admin-input">{{ old('description', $plan->description) }}</textarea>
</div>
<div class="form-grid-2">
    <div class="form-row">
        <label>Kode Paket <span class="req">*</span></label>
        <select name="code" required class="admin-input">
            <option value="app" @selected(old('code', $plan->code) === 'app')>app</option>
            <option value="marketplace" @selected(old('code', $plan->code) === 'marketplace')>marketplace</option>
        </select>
    </div>
    <div class="form-row">
        <label>Periode <span class="req">*</span></label>
        <select name="billing_period" required class="admin-input">
            <option value="monthly" @selected(old('billing_period', $plan->billing_period) === 'monthly')>Bulanan</option>
            <option value="yearly" @selected(old('billing_period', $plan->billing_period) === 'yearly')>Tahunan</option>
        </select>
    </div>
</div>
<div class="form-grid-2">
    <div class="form-row">
        <label>Harga (Rp) <span class="req">*</span></label>
        <input type="number" name="price" value="{{ old('price', $plan->price ?: 0) }}" min="0" step="0.01" required class="admin-input">
    </div>
    <div class="form-row">
        <label>Urutan tampil</label>
        <input type="number" name="sort_order" value="{{ old('sort_order', $plan->sort_order ?: 0) }}" class="admin-input">
    </div>
</div>
<div class="form-row">
    <label>Fitur (satu per baris)</label>
    <textarea name="features" rows="5" class="admin-input">{{ old('features', is_array($plan->features) ? implode("\n", $plan->features) : '') }}</textarea>
    <small class="form-help">Tulis tiap fitur di baris baru. Akan otomatis tersimpan sebagai daftar.</small>
</div>
<div class="form-row">
    <label class="switch">
        <input type="checkbox" name="includes_marketplace" value="1" @checked(old('includes_marketplace', $plan->includes_marketplace))>
        <span class="switch-slider"></span>
        <span class="switch-label">Termasuk akses Marketplace</span>
    </label>
</div>
<div class="form-row">
    <label>Diskon Upgrade (%)</label>
    <input type="number" name="upgrade_discount_percent" value="{{ old('upgrade_discount_percent', $plan->upgrade_discount_percent ?? 0) }}" min="0" max="100" class="admin-input">
    <small class="form-help">Berlaku otomatis untuk user yang upgrade dari paket Bookify App ke paket Marketplace ini. Default: {{ \App\Models\Plan::DEFAULT_UPGRADE_DISCOUNT_PERCENT }}% jika 0.</small>
</div>
<div class="form-row">
    <label class="switch">
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $plan->is_active ?? true))>
        <span class="switch-slider"></span>
        <span class="switch-label">Paket aktif (dapat dipilih user)</span>
    </label>
</div>
