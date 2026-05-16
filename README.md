# Compras do Bebê SEO Theme

## Instalação
1. Compacte a pasta do tema em ZIP.
2. No WordPress: Aparência > Temas > Adicionar novo > Enviar tema.
3. Ative o tema.

## Configurações rápidas
- **Logo:** Aparência > Personalizar > Identidade do site.
- **Menus:** Aparência > Menus (locais: principal e rodapé).
- **Cores e layout:** `assets/css/main.css`.
- **Categorias da home:** `front-page.php` (slugs técnicos).

## Integrações
- **Rank Math:** evita duplicação de title/meta/canonical/schema. Fallback apenas sem Rank Math.
- **YARPP:** usa YARPP como padrão para relacionados; fallback só sem plugin.
- **WP Popular Posts:** seção "Mais acessados" só aparece se plugin ativo e com dados.

## Gerar ZIP
```bash
zip -r compras-do-bebe-seo-theme.zip . -x '*.git*' -x 'node_modules/*'
```

## Checklist de validação
- Home com hero compacto e seções editoriais.
- Busca e URLs problemáticas com `noindex,follow`.
- Single com avisos, autor e relacionados.
- Layout mobile sem overflow em 360/390/768.
