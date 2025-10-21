document.addEventListener('DOMContentLoaded', function () {
    // Función para mostrar alertas
    function showAlert(type, message) {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
        alertDiv.style.position = 'fixed';
        alertDiv.style.top = '20px';
        alertDiv.style.right = '20px';
        alertDiv.style.zIndex = '9999';
        alertDiv.style.minWidth = '300px';

        alertDiv.innerHTML = `
            <strong>${type === 'success' ? 'Éxito!' : 'Error!'}</strong> ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;

        document.body.appendChild(alertDiv);

        // Auto-hide después de 5 segundos
        setTimeout(function () {
            if (alertDiv && alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 5000);
    }

    // Confirmación para acciones importantes
    document.querySelectorAll('[data-confirm]').forEach(function (element) {
        element.addEventListener('click', function (e) {
            const message = this.getAttribute('data-confirm');
            if (!confirm(message)) {
                e.preventDefault();
            }
        });
    });

    // Formateo automático de montos
    document.querySelectorAll('input[name="monto"]').forEach(function (input) {
        input.addEventListener('blur', function () {
            const value = parseFloat(this.value);
            if (!isNaN(value)) {
                this.value = value.toFixed(2);
            }
        });
    });

    // Validación de formulario de pago
    const formPago = document.querySelector('form[action="registrar_pago.php"]');
    if (formPago) {
        formPago.addEventListener('submit', function (e) {
            let errores = [];

            const nombre = this.querySelector('[name="nombre_estudiante"]').value.trim();
            const curso = this.querySelector('[name="curso"]').value;
            const concepto = this.querySelector('[name="mes_pago"]').value;
            const monto = parseFloat(this.querySelector('[name="monto"]').value);

            if (!nombre) {
                errores.push('El nombre del estudiante es requerido');
            }

            if (!curso) {
                errores.push('El curso es requerido');
            }

            if (!concepto) {
                errores.push('El concepto es requerido');
            }

            if (isNaN(monto) || monto <= 0) {
                errores.push('El monto debe ser un número mayor a cero');
            }

            if (errores.length > 0) {
                e.preventDefault();
                showAlert('danger', errores.join('. '));
            }
        });
    }

    // Búsqueda en tiempo real (opcional)
    const searchInput = document.querySelector('input[name="buscar"]');
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function () {
            clearTimeout(searchTimeout);
            const query = this.value.trim();

            if (query.length >= 3) {
                searchTimeout = setTimeout(function () {
                    // Aquí se podría implementar búsqueda AJAX
                }, 500);
            }
        });
    }

    // Animaciones suaves para las tarjetas de estadísticas
    const statCards = document.querySelectorAll('.stat-card');
    const observer = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) {
                entry.target.style.animationPlayState = 'running';
            }
        });
    });

    statCards.forEach(function (card) {
        observer.observe(card);
    });

    // Tooltip para botones
    const tooltipElements = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    tooltipElements.forEach(function (element) {
        new bootstrap.Tooltip(element);
    });

    // Confirmación antes de cerrar sesión
    const logoutLink = document.querySelector('a[href="logout.php"]');
    if (logoutLink) {
        logoutLink.addEventListener('click', function (e) {
            if (!confirm('¿Está seguro que desea cerrar sesión?')) {
                e.preventDefault();
            }
        });
    }

    // Actualizar fecha y hora en tiempo real (opcional)
    function updateDateTime() {
        const now = new Date();
        const dateTimeElements = document.querySelectorAll('.current-datetime');
        dateTimeElements.forEach(function (element) {
            element.textContent = now.toLocaleString('es-ES');
        });
    }

    updateDateTime(); // Llamar inmediatamente para evitar espera de 1s
    setInterval(updateDateTime, 1000);

    // Mejorar UX del formulario
    const formInputs = document.querySelectorAll('.form-control');
    formInputs.forEach(function (input) {
        input.addEventListener('focus', function () {
            if (this.parentNode) {
                this.parentNode.style.transform = 'scale(1.02)';
                this.parentNode.style.transition = 'transform 0.2s ease';
            }
        });

        input.addEventListener('blur', function () {
            if (this.parentNode) {
                this.parentNode.style.transform = 'scale(1)';
            }
        });
    });
});

// Función global para recargar estadísticas (si se implementa AJAX)
function reloadStats() {
    console.log('Reloading statistics...');
}

// Función para exportar datos (futuro)
function exportData(format) {
    console.log(`Exporting data in ${format} format...`);
}
