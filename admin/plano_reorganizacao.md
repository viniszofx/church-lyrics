# Plano de Reorganização do Sistema Church Lyrics

## 1. Estrutura de Pastas Criada

```
admin/
├── includes/            # Arquivos comuns como cabeçalhos e rodapés
│   ├── header.php
│   └── footer.php
│
├── modules/             # Pasta principal para os módulos funcionais
│   ├── tempos_liturgicos/
│   ├── datas_comemorativas/
│   ├── momentos_missa/
│   ├── musicas/
│   ├── cifras/
│   ├── videos/
│   ├── missas/
│   ├── igrejas/
│   ├── sacerdotes/
│   ├── vinculos/
│   └── relatorios/
│
└── index.php            # Página principal do painel administrativo
```

## 2. Convenção de Nomenclatura de Arquivos

Para cada módulo, estamos seguindo um padrão de nomenclatura consistente:

- `list.php` - Lista todos os registros
- `form.php` - Formulário para adicionar ou editar registros
- `salvar.php` - Processa o salvamento do formulário
- `excluir.php` - Exclui um registro
- `visualizar.php` - Visualiza detalhes de um registro (quando aplicável)

## 3. Modificações em Arquivos

Para cada arquivo movido para um módulo:

1. Atualizamos os caminhos de inclusão para:

   - `include_once("../../conexao.php")` para arquivos de processamento
   - `include_once("../includes/header.php")` e `include_once("../includes/footer.php")` para arquivos de interface

2. Atualizamos os links de navegação para apontar para os novos caminhos

3. Adicionamos verificações de erro adicionais e tratamento de exceções

4. Padronizamos todos os nomes de tabelas para minúsculas conforme exigido pelo MySQL

## 4. Instruções para Implementação

Para implementar esta reorganização:

1. **Arquivos já criados**:

   - Módulo de sacerdotes: `list.php`, `form.php`, `salvar.php` e `excluir.php`
   - Módulo de igrejas: `list.php`
   - Arquivos comuns: `header.php` e `footer.php`
   - Novo `index.php` (como `index_new.php`)

2. **Próximos passos**:

   - Mover os arquivos restantes para seus respectivos módulos seguindo o padrão estabelecido
   - Atualizar todos os links internos para apontar para os novos caminhos
   - Testar cada módulo após a migração para garantir que estão funcionando corretamente
   - Substituir o index.php original pelo novo após a conclusão da migração

3. **Considerações de implementação**:
   - Renomear arquivos conforme o novo padrão (ex: `sacerdote_list.php` → `list.php`)
   - Atualizar todos os redirecionamentos para refletir os novos caminhos
   - Garantir que todas as tabelas sejam referenciadas em minúsculas para evitar erros de caso

## 5. Benefícios da Reorganização

- **Melhor organização**: Arquivos agrupados por funcionalidade
- **Manutenção simplificada**: Mais fácil encontrar e modificar código relacionado
- **Consistência**: Padrão uniforme para nomes de arquivos e estrutura de código
- **Escalabilidade**: Mais fácil adicionar novos módulos ou funcionalidades
- **Menor repetição de código**: Uso de arquivos comuns como header e footer

## 6. Testes Recomendados

Após implementar cada módulo, testar:

1. Listagem de registros
2. Adição de novos registros
3. Edição de registros existentes
4. Exclusão de registros
5. Navegação entre módulos
6. Funcionalidades específicas de cada módulo (ex: visualização de cifras)
