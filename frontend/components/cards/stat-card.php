<div class="stat-card">
    <div class="stat-icon <?= $iconClass ?? 'blue' ?>">
        <i class="fas <?= $icon ?? 'fa-chart-line' ?>"></i>
    </div>
    <div class="stat-info">
        <h3><?= $value ?? 0 ?></h3>
        <p><?= $label ?? '' ?></p>
    </div>
</div>

<style>
.stat-card {
    background: white;
    border-radius: var(--border-radius);
    padding: 20px;
    box-shadow: var(--shadow);
    display: flex;
    align-items: center;
    gap: 15px;
    transition: var(--transition);
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-hover);
}

.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
}

.stat-icon.blue { background: #e3f2fd; color: #1976d2; }
.stat-icon.green { background: #e8f5e9; color: #2e7d32; }
.stat-icon.orange { background: #fff3e0; color: #ed6c02; }
.stat-icon.purple { background: #f3e5f5; color: #9c27b0; }
.stat-icon.red { background: #ffebee; color: #d32f2f; }
.stat-icon.teal { background: #e0f2f1; color: #00695c; }

.stat-info {
    flex: 1;
}

.stat-info h3 {
    font-size: 24px;
    margin: 0;
    color: var(--text-color);
}

.stat-info p {
    margin: 0;
    color: var(--text-light);
    font-size: 14px;
}
</style>