<div class="row">
    <!-- Bloco 1: Dados do Munícipe -->
    <div class="col-md-12 mb-4">
        <h4 class="text-primary border-bottom pb-2">Dados do Munícipe</h4>
        <input type="hidden" name="municipe_id" :value="municipeId">
        
        <div class="row mt-3">
            <div class="col-md-6 mb-3">
                <label for="nome" class="form-label">Nome Completo <span class="text-danger">*</span></label>
                <input type="text" id="nome" name="nome" class="form-control" v-model="nome" :readonly="isEditMode" required>
            </div>
            
            <div class="col-md-3 mb-3">
                <label for="data_nascimento" class="form-label">Data de Nascimento <span class="text-danger">*</span></label>
                <input type="date" id="data_nascimento" name="data_nascimento" class="form-control" v-model="data_nascimento" :readonly="isEditMode" required>
            </div>

            <div class="col-md-3 mb-3">
                <label for="telefone" class="form-label">Telefone</label>
                <input type="text" id="telefone" name="telefone" class="form-control" v-model="telefone" :readonly="isEditMode">
            </div>
        </div>

        <div class="row">
            <div class="col-md-2 mb-3">
                <label for="cep" class="form-label">CEP <span class="text-danger">*</span></label>
                <input type="text" id="cep" name="cep" class="form-control" v-model="cep" :readonly="isEditMode" required placeholder="00000-000">
            </div>

            <div class="col-md-5 mb-3">
                <label for="endereco" class="form-label">Endereço Completo</label>
                <input type="text" id="endereco" name="endereco" class="form-control" v-model="endereco" readonly>
            </div>
            
            <div class="col-md-3 mb-3">
                <label for="bairro" class="form-label">Bairro <span class="text-danger">*</span></label>
                <input type="text" id="bairro" name="bairro" class="form-control" v-model="bairro" readonly placeholder="Ex: Centro">
            </div>

            <div class="col-md-3 mb-3 d-flex align-items-center mt-4">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="possui_filhos" name="possui_filhos" v-model="possui_filhos" :disabled="isEditMode">
                    <label class="form-check-label" for="possui_filhos">
                        Possui Filhos?
                    </label>
                </div>
            </div>
        </div>
    </div>

    <!-- Bloco 2: Itens Doados -->
    <div class="col-md-12 mb-4">
        <h4 class="text-success border-bottom pb-2">Itens Doados (Visita Atual)</h4>
        <div class="alert alert-info py-2 mb-4"><i class="fa-solid fa-circle-info"></i> O sistema limita automaticamente a saída à quantidade disponível em estoque.</div>
        
        <div class="row mt-3">
            <div v-if="produtosEstoque.length === 0" class="col-12 text-center text-muted py-3">
                <i class="fa-solid fa-spinner fa-spin"></i> Carregando estoque disponível...
            </div>
            <div v-for="produto in produtosEstoque" :key="produto.id" class="col-md-2 mb-3">
                <label :for="'qtd_' + produto.id" class="form-label text-truncate d-block" :title="produto.categoria">@{{ produto.categoria }}</label>
                <input type="number" :id="'qtd_' + produto.id" class="form-control" v-model.number="produto.qtd_saida" min="0" :max="produto.quantidade_atual" :disabled="produto.quantidade_atual === 0">
                <small :class="['fw-bold', produto.quantidade_atual === 0 ? 'text-danger' : 'text-success']">Saldo: @{{ produto.quantidade_atual }}</small>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-md-12 mb-3">
                <label for="outros_materiais" class="form-label">Outros Materiais (Descrição)</label>
                <textarea id="outros_materiais" name="outros_materiais" class="form-control" rows="3" v-model="outros_materiais" placeholder="Ex: Brinquedos, utensílios domésticos..."></textarea>
            </div>
        </div>
    </div>

    <!-- Bloco 3: Finalização -->
    <div class="col-md-12">
        <h4 class="text-secondary border-bottom pb-2">Finalização</h4>
        <div class="row mt-3">
            <div class="col-md-6 mb-3">
                <label for="autorizado_por" class="form-label">Autorizado Por (Atendente Atual)</label>
                <input type="text" id="autorizado_por" name="autorizado_por" class="form-control" v-model="autorizado_por" readonly>
            </div>
            
            <div class="col-md-6 mb-3 d-flex align-items-center mt-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="assinatura_recebedor" name="assinatura_recebedor" v-model="assinatura_recebedor" value="1">
                    <label class="form-check-label" for="assinatura_recebedor">
                        Munícipe assinou o termo de recebimento?
                    </label>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-12 text-end mt-4">
        <button type="submit" class="btn btn-primary btn-lg" :disabled="!isIntervaloValido">
            <i class="fa-solid fa-check"></i> Registrar Atendimento e Saída
        </button>
    </div>
</div>
