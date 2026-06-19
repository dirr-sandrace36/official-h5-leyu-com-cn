<?php
/**
 * Site metadata configuration and description generator.
 * Provides structured site information and generates brief description text.
 */

class SiteMeta
{
    private array $metaData;

    public function __construct()
    {
        $this->metaData = [
            'site_name'        => '乐鱼体育',
            'site_url'         => 'https://official-h5-leyu.com.cn',
            'keywords'         => ['乐鱼体育', '体育赛事', '在线娱乐', '竞猜平台'],
            'description'      => '乐鱼体育提供丰富的体育赛事资讯与互动体验。',
            'language'         => 'zh-CN',
            'charset'          => 'UTF-8',
            'author'           => 'Admin',
            'version'          => '1.0',
            'creation_date'    => '2025-04-01',
            'last_modified'    => '2025-04-10',
        ];
    }

    private function sanitizeOutput(string $input): string
    {
        return htmlspecialchars($input, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    private function getKeywordString(): string
    {
        $keywords = $this->metaData['keywords'] ?? [];
        return implode(', ', $keywords);
    }

    private function buildDescriptionBase(): string
    {
        $name = $this->metaData['site_name'] ?? '';
        $desc = $this->metaData['description'] ?? '';
        $url  = $this->metaData['site_url'] ?? '';

        return sprintf(
            '%s - %s 官方网站: %s',
            $desc,
            $name,
            $url
        );
    }

    public function generateShortDescription(): string
    {
        $base = $this->buildDescriptionBase();
        $kw   = $this->getKeywordString();

        if (!empty($kw)) {
            $base .= ' 关键词：' . $kw;
        }

        return $this->sanitizeOutput($base);
    }

    public function getMetaByKey(string $key): ?string
    {
        if (array_key_exists($key, $this->metaData)) {
            $value = $this->metaData[$key];
            if (is_array($value)) {
                return $this->sanitizeOutput(implode(', ', $value));
            }
            return $this->sanitizeOutput((string) $value);
        }
        return null;
    }

    public function getAllMeta(): array
    {
        $result = [];
        foreach ($this->metaData as $key => $value) {
            if (is_array($value)) {
                $result[$key] = $this->sanitizeOutput(implode(', ', $value));
            } else {
                $result[$key] = $this->sanitizeOutput((string) $value);
            }
        }
        return $result;
    }

    public function renderMetaTags(): string
    {
        $tags = '';
        $tags .= '<meta charset="' . $this->sanitizeOutput($this->metaData['charset'] ?? 'UTF-8') . '">' . "\n";
        $tags .= '<meta name="description" content="' . $this->generateShortDescription() . '">' . "\n";
        $tags .= '<meta name="keywords" content="' . $this->sanitizeOutput($this->getKeywordString()) . '">' . "\n";
        $tags .= '<meta name="author" content="' . $this->sanitizeOutput($this->metaData['author'] ?? '') . '">' . "\n";
        $tags .= '<meta name="viewport" content="width=device-width, initial-scale=1.0">' . "\n";
        return $tags;
    }
}

// Example usage
$siteMeta = new SiteMeta();

echo "Short Description:\n";
echo $siteMeta->generateShortDescription() . "\n\n";

echo "All Meta Data:\n";
print_r($siteMeta->getAllMeta());

echo "\nMeta Tags HTML:\n";
echo $siteMeta->renderMetaTags();

echo "\nSingle Key (site_url):\n";
echo $siteMeta->getMetaByKey('site_url') . "\n";