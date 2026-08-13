export type ScalarKind = 'string' | 'number' | 'boolean' | 'null';

export type LiteralValue = string | number | boolean | null;

export interface AnyDescriptor {
  readonly kind: 'any';
}

export interface ScalarDescriptor {
  readonly kind: ScalarKind;
  readonly minimum?: number;
  readonly maximum?: number;
  readonly integer?: boolean;
  readonly format?: 'byte' | 'meta-key' | 'uri' | 'uri-template';
}

export interface LiteralDescriptor {
  readonly kind: 'literal';
  readonly value: LiteralValue;
}

export interface ReferenceDescriptor {
  readonly kind: 'ref';
  readonly name: string;
}

export interface ListDescriptor {
  readonly kind: 'list';
  readonly items: Descriptor;
}

export interface TupleDescriptor {
  readonly kind: 'tuple';
  readonly items: readonly Descriptor[];
}

export interface MapDescriptor {
  readonly kind: 'map';
  readonly values: Descriptor;
  readonly keyFormat?: 'meta-key';
}

export interface AnyPresentConstraint {
  readonly kind: 'any-present';
  readonly fields: readonly string[];
}

export type RecordConstraint = AnyPresentConstraint;

export interface FieldDescriptor {
  readonly required: boolean;
  readonly type: Descriptor;
}

export interface RecordDescriptor {
  readonly kind: 'record';
  readonly fields: Readonly<Record<string, FieldDescriptor>>;
  readonly parents: readonly Descriptor[];
  readonly additional: Descriptor | false;
  readonly constraints?: readonly RecordConstraint[];
}

export interface UnionDescriptor {
  readonly kind: 'union';
  readonly anyOf: readonly Descriptor[];
}

export interface IntersectionDescriptor {
  readonly kind: 'intersection';
  readonly allOf: readonly Descriptor[];
}

export interface OmitDescriptor {
  readonly kind: 'omit';
  readonly from: Descriptor;
  readonly keys: readonly string[];
}

export type Descriptor =
  | AnyDescriptor
  | ScalarDescriptor
  | LiteralDescriptor
  | ReferenceDescriptor
  | ListDescriptor
  | TupleDescriptor
  | MapDescriptor
  | RecordDescriptor
  | UnionDescriptor
  | IntersectionDescriptor
  | OmitDescriptor;

export interface CompiledRevision {
  readonly revision: string;
  readonly constants: Readonly<Record<string, LiteralValue>>;
  readonly descriptors: Readonly<Record<string, Descriptor>>;
  readonly rootRecordTypes: readonly string[];
}

export interface DescriptorBundle {
  readonly pool: Readonly<Record<string, Descriptor>>;
  readonly manifests: Readonly<Record<string, RevisionManifest>>;
  readonly revisions: Readonly<Record<string, CompiledRevision>>;
}

export interface RevisionManifest {
  readonly fingerprint: string;
  readonly roots: readonly string[];
  readonly types: Readonly<Record<string, string>>;
}
