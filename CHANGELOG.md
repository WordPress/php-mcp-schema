# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added

- Exact MCP `2025-11-25` and `2026-07-28` DTO trees under `WP\McpSchema\V20251125\` and `WP\McpSchema\V20260728\`, including modern per-request metadata, `resultType`, MRTR result types, deprecation annotations, and numeric JSON-RPC error constants.
- Exact-wire fixtures and same-process co-loading checks for both revisions.

### Changed

- **Breaking:** all imports must include a revision namespace segment. Existing 0.1.x consumers should migrate `WP\McpSchema\...` imports to `WP\McpSchema\V20251125\...` before adopting the modern tree.
- The generator now builds every shipping revision by default, cleans only the matching revision tree, validates shipping configs against revision history, and generates the root skill reference deliberately from `2026-07-28`.

### Removed

- The ambiguous unqualified `WP\McpSchema\...` DTO tree.

## [0.1.3] - 2026-08-10

### Fixed

- Open MCP schema types now preserve unrecognized fields during `fromArray()` and `toArray()` round trips, including additional JSON Schema keywords such as `$defs` in tool input and output schemas.

## [0.1.2] - 2026-06-05

### Fixed

- `ToolInputSchema::toArray()` and `ToolOutputSchema::toArray()` now always emit the `properties` key as a JSON object, even for tools that declare no parameters. Parameter-less tools previously produced `{"type":"object"}` (key omitted) or `[]` (JSON array), both of which strict JSON Schema validators such as OpenAI strict function-calling mode reject with `object schema missing properties`. Tools with parameters are unchanged. The fix is mirrored in the TypeScript generator so a clean regeneration reproduces the patched output.

## [0.1.1] - 2026-04-10

### Fixed

- Exclude `skill/` directory from Composer dist archives. It contains dev-only helper scripts and reference docs that have no runtime purpose for package consumers and should not ship in the `vendor/` folder.
- Exclude `CLAUDE.md` from Composer dist archives. It contains dev-only project instructions with no runtime purpose for package consumers.

## [0.1.0] - 2026-03-02

### Added

- PHP 7.4+ Data Transfer Objects (DTOs) for the Model Context Protocol (MCP) 2025-11-25 specification
- `fromArray()` static factory methods for deserializing arrays into typed DTOs
- `toArray()` methods for serializing DTOs to arrays suitable for `json_encode()`
- Factory classes for union/polymorphic type resolution
- Class-based enums for MCP enumerated values (PHP 7.4 compatible)
- Complete MCP domain coverage: Server (Tools, Resources, Prompts, Logging), Client (Sampling, Elicitation, Roots), Common (JSON-RPC, Protocol, Content)
- JSON-RPC 2.0 message types (Request, Notification, Result, Error)
- PSR-4 autoloading under `WP\McpSchema\` namespace
- PHPStan level max static analysis validation
