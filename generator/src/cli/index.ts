#!/usr/bin/env node
/**
 * MCP PHP Schema Generator - CLI
 *
 * Command-line interface for generating PHP DTOs from MCP TypeScript schema.
 */

import { Command } from 'commander';
import chalk from 'chalk';
import ora from 'ora';
import { generate } from '../index.js';
import type { GenerateOptions } from '../index.js';
import { createConfig, DEFAULT_SCHEMA_SOURCE, loadConfigFromFile, listConfigVersions, loadShippingRevisionConfigs } from '../config/index.js';
import { clearCache } from '../fetcher/index.js';

const program = new Command();

program
  .name('mcp-php-generator')
  .description('Generate PHP 7.4 DTOs from MCP TypeScript schema')
  .version('1.0.0');

// Generate command
program
  .command('generate')
  .description('Generate PHP DTOs from MCP schema')
  .option('-c, --config <file>', 'Generate one revision from a configuration file')
  .option('-o, --output <dir>', 'Output directory (overrides config)')
  .option('-n, --namespace <ns>', 'PHP namespace (overrides config)')
  .option('--dry-run', 'Show what would be generated without writing files')
  .option('--fresh', 'Force fresh fetch from GitHub (ignore cache)')
  .option('--verbose', 'Enable verbose output')
  .action(async (options: Record<string, unknown>) => {
    const spinner = ora('Initializing...').start();

    try {
      const configFile = options['config'] as string | undefined;
      if (!configFile && (options['output'] || options['namespace'])) {
        throw new Error('--output and --namespace require --config for an isolated revision');
      }

      spinner.text = configFile
        ? `Loading config from ${configFile}...`
        : 'Loading shipping revision configs...';
      const baseConfigs = configFile
        ? [loadConfigFromFile(configFile)]
        : loadShippingRevisionConfigs();

      for (const baseConfig of baseConfigs) {
        const outputConfig = { ...baseConfig.output };
        if (options['output']) outputConfig.outputDir = options['output'] as string;
        if (options['namespace']) outputConfig.namespace = options['namespace'] as string;

        const config = createConfig({
          schema: { ...baseConfig.schema },
          output: outputConfig,
          skill: { ...baseConfig.skill },
          verbose: Boolean(options['verbose']) || baseConfig.verbose,
          dryRun: Boolean(options['dryRun']) || baseConfig.dryRun,
        });

        const generateOptions: GenerateOptions = {
          fresh: options['fresh'] as boolean,
        };

        spinner.text = `Generating ${config.schema.version}...`;
        const result = await generate(config, generateOptions);

        console.log('');
        console.log(chalk.bold(`Summary for ${config.schema.version}:`));
        console.log(`  ${chalk.green('✓')} DTOs: ${result.stats.dtos}`);
        console.log(`  ${chalk.green('✓')} Enums: ${result.stats.enums}`);
        console.log(`  ${chalk.green('✓')} Unions: ${result.stats.unions}`);
        console.log(`  ${chalk.green('✓')} Factories: ${result.stats.factories}`);
        console.log(`  ${chalk.green('✓')} Builders: ${result.stats.builders}`);
        console.log(`  ${chalk.blue('⏱')} Duration: ${result.stats.duration}ms`);

        if (result.errors.length > 0) {
          console.log(chalk.yellow('Warnings:'));
          for (const error of result.errors) {
            console.log(`  ${chalk.yellow('!')} ${error.message}`);
          }
        }
      }

      spinner.succeed(`Generation complete for ${baseConfigs.length} revision(s)!`);
    } catch (error) {
      spinner.fail('Generation failed');
      const message = error instanceof Error ? error.message : String(error);
      console.error(chalk.red(`Error: ${message}`));
      process.exit(1);
    }
  });

// Clear cache command
program
  .command('clear-cache')
  .description('Clear the schema cache')
  .action(async () => {
    const spinner = ora('Clearing cache...').start();

    try {
      await clearCache();
      spinner.succeed('Cache cleared');
    } catch (error) {
      spinner.fail('Failed to clear cache');
      const message = error instanceof Error ? error.message : String(error);
      console.error(chalk.red(`Error: ${message}`));
      process.exit(1);
    }
  });

// Info command
program
  .command('info')
  .description('Show generator information')
  .action(() => {
    console.log(chalk.bold('MCP PHP Schema Generator'));
    console.log('');
    console.log('Generates PHP 7.4 DTOs from the Model Context Protocol TypeScript schema.');
    console.log('');
    console.log(chalk.bold('Default Configuration:'));
    console.log(`  Schema Repository: ${DEFAULT_SCHEMA_SOURCE.repository}`);
    console.log(`  Schema Branch: ${DEFAULT_SCHEMA_SOURCE.branch}`);
    console.log('  Output Directory: ../src/V<YYYYMMDD>');
    console.log('  PHP Namespace: WP\\McpSchema\\V<YYYYMMDD>');
    console.log(`  Target PHP: 7.4 (for maximum compatibility)`);
    console.log('');

    const versions = listConfigVersions();
    if (versions.length > 0) {
      console.log(chalk.bold('Available Config Files:'));
      for (const version of versions) {
        console.log(`  - ${version}.json`);
      }
      console.log('');
    }

    console.log(chalk.yellow('Note: generate without --config builds every shipping revision.'));
    console.log('');
    console.log('For more information, see: https://github.com/WordPress/php-mcp-schema');
  });

// List configs command
program
  .command('configs')
  .description('List available configuration files')
  .action(() => {
    const versions = listConfigVersions();
    if (versions.length === 0) {
      console.log(chalk.yellow('No configuration files found in config/ directory.'));
      return;
    }

    console.log(chalk.bold('Available Configuration Files:'));
    console.log('');
    for (const version of versions) {
      console.log(`  ${chalk.green('•')} ${version}.json`);
    }
    console.log('');
    console.log(`Use ${chalk.cyan('--config config/<version>.json')} to generate.`);
  });

program.parse();
