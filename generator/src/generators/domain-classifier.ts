/**
 * MCP PHP Schema Generator - Domain Classifier
 *
 * Classifies MCP types into domains and subdomains based on @category tags.
 */

import type { DomainClassification, JsDocTag, CategoryMapping, McpDomain, McpSubdomain } from '../types/index.js';

/**
 * Default category to domain/subdomain mappings.
 * Derived from @category tags in the MCP TypeScript schema.
 */
const DEFAULT_CATEGORY_MAPPING: CategoryMapping = {
  // Server domain - Tools
  '`tools/list`': { domain: 'Server', subdomain: 'Tools' },
  '`tools/call`': { domain: 'Server', subdomain: 'Tools' },

  // Server domain - Resources
  '`resources/list`': { domain: 'Server', subdomain: 'Resources' },
  '`resources/templates/list`': { domain: 'Server', subdomain: 'Resources' },
  '`resources/read`': { domain: 'Server', subdomain: 'Resources' },
  '`resources/subscribe`': { domain: 'Server', subdomain: 'Resources' },
  '`resources/unsubscribe`': { domain: 'Server', subdomain: 'Resources' },

  // Server domain - Prompts
  '`prompts/list`': { domain: 'Server', subdomain: 'Prompts' },
  '`prompts/get`': { domain: 'Server', subdomain: 'Prompts' },

  // Server domain - Logging
  '`logging`': { domain: 'Server', subdomain: 'Logging' },
  '`logging/setLevel`': { domain: 'Server', subdomain: 'Logging' },

  // Server domain - Completion
  '`completion`': { domain: 'Server', subdomain: 'Core' },
  '`completion/complete`': { domain: 'Server', subdomain: 'Core' },

  // Server domain - Lifecycle
  '`lifecycle`': { domain: 'Server', subdomain: 'Lifecycle' },

  // Client domain - Sampling
  '`sampling`': { domain: 'Client', subdomain: 'Sampling' },
  '`sampling/createMessage`': { domain: 'Client', subdomain: 'Sampling' },

  // Client domain - Elicitation
  '`elicitation`': { domain: 'Client', subdomain: 'Elicitation' },
  '`elicitation/create`': { domain: 'Client', subdomain: 'Elicitation' },

  // Client domain - Roots
  '`roots`': { domain: 'Client', subdomain: 'Roots' },
  '`roots/list`': { domain: 'Client', subdomain: 'Roots' },

  // Client domain - Tasks (experimental)
  '`tasks`': { domain: 'Client', subdomain: 'Tasks' },
  '`tasks/list`': { domain: 'Common', subdomain: 'Tasks' },
  '`tasks/get`': { domain: 'Common', subdomain: 'Tasks' },
  '`tasks/cancel`': { domain: 'Common', subdomain: 'Tasks' },

  // Common domain
  '`protocol`': { domain: 'Common', subdomain: 'Protocol' },
  '`notifications`': { domain: 'Common', subdomain: 'Protocol' },
};

/**
 * Classifies MCP types into domains and subdomains.
 */
export class DomainClassifier {
  private readonly categoryMapping: CategoryMapping;
  private readonly fallbackPatterns: Array<{ pattern: RegExp; classification: DomainClassification }>;

  constructor(customMapping?: Partial<CategoryMapping>) {
    this.categoryMapping = Object.assign(
      {},
      DEFAULT_CATEGORY_MAPPING,
      customMapping ?? {}
    ) as CategoryMapping;

    // Fallback patterns for types without @category tags
    this.fallbackPatterns = [
      // Server patterns
      { pattern: /^(Tool|ListTools|CallTool)/i, classification: { domain: 'Server', subdomain: 'Tools' } },
      { pattern: /^(Resource|ListResources|ReadResource|Subscribe|Unsubscribe)/i, classification: { domain: 'Server', subdomain: 'Resources' } },
      { pattern: /^(Prompt|ListPrompts|GetPrompt)/i, classification: { domain: 'Server', subdomain: 'Prompts' } },
      { pattern: /^(Log|SetLevel|LoggingLevel)/i, classification: { domain: 'Server', subdomain: 'Logging' } },
      { pattern: /^(Complete|Completion)/i, classification: { domain: 'Server', subdomain: 'Core' } },
      { pattern: /^Server(?!Request)/i, classification: { domain: 'Server', subdomain: 'Lifecycle' } },

      // Client patterns
      { pattern: /^(Sample|CreateMessage|ModelPreferences|ModelHint)/i, classification: { domain: 'Client', subdomain: 'Sampling' } },
      { pattern: /^(Elicit|Elicitation)/i, classification: { domain: 'Client', subdomain: 'Elicitation' } },
      { pattern: /^(Root|ListRoots)/i, classification: { domain: 'Client', subdomain: 'Roots' } },
      { pattern: /^Client(?!Request|Notification)/i, classification: { domain: 'Client', subdomain: 'Lifecycle' } },

      // Common patterns
      { pattern: /^(Task)/i, classification: { domain: 'Common', subdomain: 'Tasks' } },
      { pattern: /^(Text|Image|Audio|Embedded|Resource)Content/i, classification: { domain: 'Common', subdomain: 'Content' } },
      { pattern: /^(JSONRPC|Request|Response|Notification|Error)/i, classification: { domain: 'Common', subdomain: 'JsonRpc' } },
      { pattern: /^(Initialize|Ping|Progress|Cancel)/i, classification: { domain: 'Common', subdomain: 'Protocol' } },
      { pattern: /^Implementation$/i, classification: { domain: 'Common', subdomain: 'Lifecycle' } },
      { pattern: /^Icon$/i, classification: { domain: 'Common', subdomain: 'Core' } },
    ];
  }

  /**
   * Cache of previously classified types for synthetic type resolution.
   */
  private readonly classificationCache = new Map<string, DomainClassification>();

  /**
   * Classifies a type based on its @category tag or name.
   * For synthetic types, can optionally use the parent's classification.
   */
  classify(typeName: string, tags: readonly JsDocTag[], syntheticParent?: string): DomainClassification {
    // Check cache first
    if (this.classificationCache.has(typeName)) {
      return this.classificationCache.get(typeName)!;
    }

    // For synthetic types, try to use parent's classification
    if (syntheticParent && this.classificationCache.has(syntheticParent)) {
      const parentClassification = this.classificationCache.get(syntheticParent)!;
      this.classificationCache.set(typeName, parentClassification);
      return parentClassification;
    }
    // First, try to use @category tag
    const categoryTag = tags.find((tag) => tag.tagName === 'category');
    if (categoryTag?.text) {
      const mapping = this.categoryMapping[categoryTag.text];
      if (mapping) {
        this.classificationCache.set(typeName, mapping);
        return mapping;
      }
    }

    // Fallback to name-based classification
    for (const { pattern, classification } of this.fallbackPatterns) {
      if (pattern.test(typeName)) {
        this.classificationCache.set(typeName, classification);
        return classification;
      }
    }

    // Default to Common/Protocol if no match
    const defaultClassification: DomainClassification = { domain: 'Common', subdomain: 'Protocol' };
    this.classificationCache.set(typeName, defaultClassification);
    return defaultClassification;
  }

  /**
   * Gets the PHP namespace for a domain/subdomain.
   */
  getNamespace(domain: McpDomain, subdomain: McpSubdomain, version: string): string {
    return `Mcp\\Schema\\${version.replace(/-/g, '_')}\\${domain}\\${subdomain}`;
  }

  /**
   * Gets the file path for a type.
   */
  getFilePath(
    domain: McpDomain,
    subdomain: McpSubdomain,
    typeCategory: 'Dto' | 'Enum' | 'Union' | 'Factory' | 'Builder',
    className: string,
    version: string
  ): string {
    return `Schema/${version}/${domain}/${subdomain}/${typeCategory}/${className}.php`;
  }

  /**
   * Adds a custom category mapping.
   */
  addMapping(category: string, classification: DomainClassification): void {
    this.categoryMapping[category] = classification;
  }

  /**
   * Gets all known categories.
   */
  getKnownCategories(): string[] {
    return Object.keys(this.categoryMapping);
  }

  /**
   * Checks if a type is internal (has @internal tag).
   */
  isInternal(tags: readonly JsDocTag[]): boolean {
    return tags.some((tag) => tag.tagName === 'internal');
  }

  /**
   * Gets the @since version from tags.
   */
  getSinceVersion(tags: readonly JsDocTag[]): string | undefined {
    const sinceTag = tags.find((tag) => tag.tagName === 'since');
    return sinceTag?.text;
  }

  /**
   * Extracts the method/operation name from @category tag.
   */
  extractMethodName(tags: readonly JsDocTag[]): string | undefined {
    const categoryTag = tags.find((tag) => tag.tagName === 'category');
    if (!categoryTag?.text) {
      return undefined;
    }

    // Extract from backtick-wrapped value like `tools/call`
    const match = categoryTag.text.match(/`([^`]+)`/);
    return match?.[1];
  }
}
