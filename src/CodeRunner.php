<?php

declare(strict_types=1);

namespace fishze;

use ZM\Utils\ZMRequest;

class CodeRunner
{

    private array $HEADERS = [
        "Authorization" => "Token 0123456-789a-bcde-f012-3456789abcde",
        "content-type" => "application/",
        "User-Agent" => "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36",
    ];
    private array $LANGUAGES = [
        'assembly' => ['assembly', 'asm'],
        'ats' => ['ats', 'dats'],
        'bash' => ['bash', 'sh'],
        'c' => ['c', 'c'],
        'clojure' => ['clojure', 'clj'],
        'cobol' => ['cobol', 'cob'],
        'coffeescript' => ['coffeescript', 'coffee'],
        'cpp' => ['cpp', 'cpp'],
        'crystal' => ['crystal', 'cr'],
        'c#' => ['csharp', 'cs'],
        'd' => ['d', 'd'],
        'elixir' => ['elixir', 'ex'],
        'elm' => ['elm', 'elm'],
        'erlang' => ['erlang', 'erl'],
        'f#' => ['fsharp', 'fs'],
        'go' => ['go', 'go'],
        'groovy' => ['groovy', 'groovy'],
        'hare' => ['hare', 'ha'],
        'haskell' => ['haskell', 'hs'],
        'idris' => ['idris', 'idr'],
        'java' => ['java', 'java'],
        'javascript' => ['javascript', 'js'],
        'julia' => ['julia', 'jl'],
        'kotlin' => ['kotlin', 'kt'],
        'lua' => ['lua', 'lua'],
        'mercury' => ['mercury', 'm'],
        'nim' => ['nim', 'nim'],
        'nix' => ['nix', 'nix'],
        'ocaml' => ['ocaml', 'ml'],
        'perl' => ['perl', 'pl'],
        'php' => ['php', 'php'],
        'python' => ['python', 'py'],
        'raku' => ['raku', 'raku'],
        'ruby' => ['ruby', 'rb'],
        'rust' => ['rust', 'rs'],
        'sac' => ['sac', 'sac'],
        'scala' => ['scala', 'scala'],
        'swift' => ['swift', 'swift'],
        'typescript' => ['typescript', 'ts'],
        'zig' => ['zig', 'zig'],
    ];

    private function getLanguage(string $l): array
    {
        return $this->LANGUAGES[$l] == NULL ? [] : $this->LANGUAGES[$l];
    }

    private function getAllLanguages(): array
    {
        return array_keys($this->LANGUAGES);
    }

    private function runCodeRequest(string $code, string $lang, string $stdin): string
    {
        $langType = $this->getLanguage($lang);
        if ($langType == []) return "";
        $response = ZMRequest::post("https://glot.io/run/$langType[0]?version=latest", header: $this->HEADERS, data: json_encode([
            "files" => [
                0 => [
                    "name" => "main.$langType[1]",
                    "content" => $code,
                ],
            ],
            "stdin" => $stdin,
            "command" => "",
        ]));
        if (!$response) return "请求失败, 请检查网络或稍后再试";
        $response = json_decode($response, true);
        return ($response == NULL || $response == []) ? "" :
            ($response["error"] != "" || $response["stdout"] != "" ? "运行成功:\n" : "运行失败:\n") .
            $response['stdout'] .
            ($response['stderr'] == "" ? "" : "\n" . $response['stderr']);
    }

    #[\BotCommand(name: 'codeRunner', match: '运行代码', alias: ["code", "代码运行", "codeRunner"])]
    #[\CommandArgument(name: 'lang', type: 'string', required: false)]
    public function codeRunnerCommand(\BotContext $ctx): void
    {
        if ($ctx->getParam('lang') == null) {
            $lang = $ctx->prompt("请选择一种语言:\n" . implode("; ", $this->getAllLanguages()), 30, "没有收到你的回复哦", ZM_PROMPT_RETURN_STRING);
        } else {
            $lang = $ctx->getParam('lang');
        }
        if (!in_array($lang, $this->getAllLanguages())) {
            $ctx->reply('没有找到这个语言哦');
            return;
        }
        $code = $ctx->prompt('请发送代码:', 30, "没有收到你的回复哦", ZM_PROMPT_RETURN_STRING);
        $stdin = $ctx->prompt('请发送输入:', 30, "没有收到你的回复哦", ZM_PROMPT_RETURN_STRING);
        $ctx->reply($this->runCodeRequest($code, $lang, $stdin));
    }
}