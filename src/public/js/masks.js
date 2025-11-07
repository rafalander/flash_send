/**
 * Utilitários de Máscara para Formulários
 * Funções reutilizáveis para aplicar máscaras em campos de formulário
 */

const Masks = {
    /**
     * Aplica máscara de CPF no formato: 000.000.000-00
     * @param {string} value - Valor a ser formatado
     * @returns {string} - Valor formatado
     */
    formatCPF: function(value) {
        if (!value) return '';
        
        // Remove todos os caracteres não numéricos
        let numbers = value.replace(/\D/g, '');
        
        // Limita a 11 dígitos
        if (numbers.length > 11) {
            numbers = numbers.slice(0, 11);
        }
        
        // Aplica a máscara
        if (numbers.length <= 11) {
            numbers = numbers.replace(/(\d{3})(\d)/, '$1.$2');
            numbers = numbers.replace(/(\d{3})(\d)/, '$1.$2');
            numbers = numbers.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
        }
        
        return numbers;
    },

    /**
     * Aplica máscara de telefone no formato: (00) 00000-0000 ou (00) 0000-0000
     * @param {string} value - Valor a ser formatado
     * @returns {string} - Valor formatado
     */
    formatTelefone: function(value) {
        if (!value) return '';
        
        // Remove todos os caracteres não numéricos
        let numbers = value.replace(/\D/g, '');
        
        // Limita a 11 dígitos
        if (numbers.length > 11) {
            numbers = numbers.slice(0, 11);
        }
        
        // Aplica a máscara baseada no tamanho
        if (numbers.length <= 10) {
            // Telefone fixo: (00) 0000-0000
            numbers = numbers.replace(/(\d{2})(\d)/, '($1) $2');
            numbers = numbers.replace(/(\d{4})(\d)/, '$1-$2');
        } else if (numbers.length === 11) {
            // Celular: (00) 00000-0000
            numbers = numbers.replace(/(\d{2})(\d)/, '($1) $2');
            numbers = numbers.replace(/(\d{5})(\d)/, '$1-$2');
        }
        
        return numbers;
    },

    /**
     * Remove formatação de CPF, retornando apenas números
     * @param {string} value - Valor formatado
     * @returns {string} - Apenas números
     */
    unformatCPF: function(value) {
        if (!value) return '';
        return value.replace(/\D/g, '');
    },

    /**
     * Remove formatação de telefone, retornando apenas números
     * @param {string} value - Valor formatado
     * @returns {string} - Apenas números
     */
    unformatTelefone: function(value) {
        if (!value) return '';
        return value.replace(/\D/g, '');
    },

    /**
     * Aplica máscara de CPF em um elemento de input
     * @param {HTMLElement|string} element - Elemento ou seletor do input
     */
    applyCPF: function(element) {
        const input = typeof element === 'string' ? document.querySelector(element) : element;
        if (!input) return;

        input.addEventListener('input', function(e) {
            e.target.value = Masks.formatCPF(e.target.value);
        });

        // Aplica máscara no valor inicial se existir
        if (input.value) {
            input.value = Masks.formatCPF(input.value);
        }
    },

    /**
     * Aplica máscara de telefone em um elemento de input
     * @param {HTMLElement|string} element - Elemento ou seletor do input
     */
    applyTelefone: function(element) {
        const input = typeof element === 'string' ? document.querySelector(element) : element;
        if (!input) return;

        input.addEventListener('input', function(e) {
            e.target.value = Masks.formatTelefone(e.target.value);
        });

        // Aplica máscara no valor inicial se existir
        if (input.value) {
            input.value = Masks.formatTelefone(input.value);
        }
    },

    /**
     * Aplica máscaras automaticamente em elementos com data-attributes
     * Procura por elementos com data-mask="cpf" ou data-mask="telefone"
     */
    autoApply: function() {
        document.querySelectorAll('[data-mask="cpf"]').forEach(function(input) {
            Masks.applyCPF(input);
        });

        document.querySelectorAll('[data-mask="telefone"]').forEach(function(input) {
            Masks.applyTelefone(input);
        });
    }
};

// Auto-aplica máscaras quando o DOM estiver pronto
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', Masks.autoApply);
} else {
    Masks.autoApply();
}

// Exporta para uso global
window.Masks = Masks;
